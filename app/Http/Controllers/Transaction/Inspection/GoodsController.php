<?php

namespace App\Http\Controllers\Transaction\Inspection;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Inspection\Goods\StoreHeaderRequest;
use App\Http\Requests\Transaction\Inspection\Goods\StoreSubDetailInspectionRequest;
use App\Http\Requests\Transaction\UpdateInspectionGoodSubDetailRequest;
use App\Models\Master\MasterProduct;
use App\Models\Master\Material;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Goods\WallThickness;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Goods\Inspection\InspectionSubDetailLog;
use App\Models\Transaction\Inspection\Goods\WallThicknessLog;
use App\Models\Transaction\Inspection\InspectionGoodsDetail;
use App\Models\Transaction\Inspection\InspectionGoodsSubDetail;
use App\Models\Transaction\Insspection\InspectionGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumConvert;
use \Mpdf\Mpdf as PDF;
class GoodsController extends Controller
{
    function index(){
        return view('transaction.inspection.goods.goods-index');
    }

    /**
     * Non-GM hanya boleh menyentuh transaksi lokasinya sendiri.
     */
    private function canAccessLocation($locationId): bool {
        if (auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            return true;
        }

        return (string) $locationId === (string) auth()->user()->kode_kantor;
    }

    /**
     * Akses baca per-shift: lokasi sendiri, GM, atau approver yang pernah
     * ditugaskan pada shift tersebut.
     */
    private function canViewShift($detailInspectionCode): bool {
        $detail = InspectionGoodsDetail::where('detail_inspection_code', $detailInspectionCode)->first();
        if (!$detail) {
            return true; // biarkan handler menangani not-found sendiri
        }
        $header = InspectionGood::where('inspection_code', $detail->inspection_code)->first();
        if ($header && $this->canAccessLocation($header->location_id)) {
            return true;
        }

        return ApprovalHistory::where('transaction_code', $detailInspectionCode)
            ->where('inspection_type', 3)
            ->where('user_id', auth()->user()->id)
            ->exists();
    }

    private function forbiddenResponse() {
        return ResponseFormatter::error(null, 'You are not allowed to access this transaction', 403);
    }

    /**
     * Kode berjalan {seq}/{PREFIX}/{bulan romawi}/{tahun 2 digit}; reset tiap
     * bulan/tahun; parsing format lama diguard supaya tidak meledak.
     */
    private function nextSequentialCode($lastCode, string $prefix): string {
        $month = NumConvert::roman((int) date('m'));
        $year = date('y');
        $sequence = 1;
        if ($lastCode) {
            $parts = explode('/', $lastCode);
            if (count($parts) >= 4 && is_numeric($parts[0]) && $parts[2] === $month && $parts[3] === (string) $year) {
                $sequence = ((int) $parts[0]) + 1;
            }
        }

        return $sequence . '/' . $prefix . '/' . $month . '/' . $year;
    }
    function getInspectionGoods(Request $request)
    {
        $query = InspectionGood::with([
            'locationRelation',
            'machineRelation',
        ])
        ->whereBetween('created_at', [
            $request->from . ' 00:00:00',
            $request->to . ' 23:59:59',
        ]);

        // GM: bisa melihat semua location; user biasa hanya lokasinya sendiri
        if (!auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            $query->where(
                'location_id',
                auth()->user()->kode_kantor
            );
        }

        $data = $query->get();

        return response()->json([
            'data' => $data,
        ]);
    }
    function getInspectionGoodShift(Request $request) {
        $header = InspectionGood::where('inspection_code', $request->inspection_code)->first();
        if ($header && !$this->canAccessLocation($header->location_id)) {
            $isApprover = ApprovalHistory::where('inspection_type', 3)
                ->where('user_id', auth()->user()->id)
                ->whereIn('transaction_code', InspectionGoodsDetail::where('inspection_code', $header->inspection_code)->pluck('detail_inspection_code'))
                ->exists();
            if (!$isApprover) {
                return $this->forbiddenResponse();
            }
        }

        $data = InspectionGoodsDetail::with([
                'subdetailRelation',
                'headerRelation.LocationRelation'
            ])->where('inspection_code', $request->inspection_code)->get();

        return response()->json([
            'data'=>$data
        ]);
    }
    function addInspectionGoodsHeader(Request $request, StoreHeaderRequest $storeHeaderRequest) {
        // try {
            $storeHeaderRequest->validated();

            // Non-GM tidak boleh membuat transaksi atas nama lokasi lain
            $locationId = $request->location_id;
            if (!auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
                $locationId = auth()->user()->kode_kantor;
            }

            $post = [
                'inspection_code'   => null, // diisi di dalam transaksi
                'user_id'           =>auth()->user()->id,
                'location_id'       =>$locationId,
                'current_approval'  => 0,
                'status'  =>0,
            ];
            // generate semua kode dengan lock baris terakhir di DALAM satu
            // transaksi — dulu query-per-iterasi tanpa lock (rawan kode kembar)
            DB::transaction(function() use(&$post) {
                $last = InspectionGood::orderBy('id','desc')->lockForUpdate()->first();
                $post['inspection_code'] = $this->nextSequentialCode($last ? $last->inspection_code : null, 'INSG');
                InspectionGood::create($post);

                $lastDetail = InspectionGoodsDetail::orderBy('id','desc')->lockForUpdate()->first();
                $base = $this->nextSequentialCode($lastDetail ? $lastDetail->detail_inspection_code : null, 'DINSG');
                $baseSeq = (int) explode('/', $base)[0];
                $suffix = substr($base, strpos($base, '/'));
                for($i = 0 ; $i < 3 ; $i++){
                    InspectionGoodsDetail::create([
                        'inspection_code'   =>$post['inspection_code'],
                        'detail_inspection_code'   =>($baseSeq + $i) . $suffix,
                        'shift' =>$i + 1,
                        'user_id'=>auth()->user()->id,
                        'status'    => 0,
                        'approval_id'    => 0,
                        'evaluation'=>''
                    ]);
                }
            });

            return ResponseFormatter::success(
                $post,
                'Machine successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }

    }
    function addSubDetailInspectionGoods(Request $request, StoreSubDetailInspectionRequest $storeSubDetailInspectionRequest) {


           // try {

            $storeSubDetailInspectionRequest->validated();
            $detail = InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code_id)->first();
            if (!$detail) {
                return ResponseFormatter::error(null, 'Inspection shift transaction not found', 404);
            }
            if ($guard = $this->rejectIfShiftLocked($detail->detail_inspection_code)) {
                return $guard;
            }
            // kode sub detail diisi di dalam transaksi (anti kode kembar)
            $ticket_code = null;
            $post = [
                'inspection_header'                 => $detail->inspection_code,
                'inspection_detail'                 => $request->detail_inspection_code_id,
                'inspection_sub_detail'             => $ticket_code,
                'machine_id'                        => $request->machine_id,
                'material_id'                       => $request->material,
                'tape_diameter'                     => $request->tape_diameter,
                'manufacturing_code'                => $request->manufacturing_code,
                'product_id'                        => $request->product,
                'length_of_pipe'                    => $request->length_of_pipe,
                'appearance_of_pipe'                => $request->appearance_of_pipe,
                'marking'                           => $request->marking,
                'condition_pipe_of_cut'             => $request->condition_pipe_of_cut,
                'angle_pipe_of_spigot'              => $request->angle_pipe_of_spigot,
                'color_of_pipes'                    => $request->color_of_pipes,
                'evaluation_spec'                   => $request->evaluation_spec,
                'time'                              => $request->time,
                'shift'                             => $detail->shift,
                'remark'                            => '',
                'user_id'                           => auth()->user()->id,
                'status'                            => 0,
                'remark'                            => $request->add_remark,
                'inspector'                            => $request->inspector,
            ];
            $post_wt = [
                'inspection_code'               => $detail->inspection_code,
                'detail_inspection_code'        => $request->detail_inspection_code_id,
                'sub_detail_inspection_code'    => $ticket_code,
                'wt_1'                          => $request->wt_1,
                'wt_2'                          => $request->wt_2,
                'wt_3'                          => $request->wt_3,
                'wt_4'                          => $request->wt_4,
                'wt_5'                          => $request->wt_5,
                'wt_6'                          => $request->wt_6,
                'wt_7'                          => $request->wt_7,
                'wt_8'                          => $request->wt_8,
            ];
            $postDetail=[
                'length'    => 1
            ];
            $post_log = [
                'inspection_header'                 => $detail->inspection_code,
                'inspection_detail'                 => $request->detail_inspection_code_id,
                'inspection_sub_detail'             => $ticket_code,
                'machine_id'                        => $request->machine_id,
                'material_id'                       => $request->material,
                'tape_diameter'                     => $request->tape_diameter,
                'manufacturing_code'                => $request->manufacturing_code,
                'product_id'                        => $request->product,
                'length_of_pipe'                    => $request->length_of_pipe,
                'appearance_of_pipe'                => $request->appearance_of_pipe,
                'marking'                           => $request->marking,
                'condition_pipe_of_cut'             => $request->condition_pipe_of_cut,
                'angle_pipe_of_spigot'              => $request->angle_pipe_of_spigot,
                'color_of_pipes'                    => $request->color_of_pipes,
                'evaluation_spec'                   => $request->evaluation_spec,
                'time'                              => $request->time,
                'shift'                             => $detail->shift,
                'remark'                            => 'created by system',
                'user_id'                           => auth()->user()->id,
                'creator'                           => auth()->user()->id,
                'status'                            => 0,
                'inspector'                         => $request->inspector,
            ];

            $post_log_wt = [
                'inspection_code'               => $detail->inspection_code,
                'detail_inspection_code'        => $request->detail_inspection_code_id,
                'sub_detail_inspection_code'    => $ticket_code,
                'wt_1'                          => $request->wt_1,
                'wt_2'                          => $request->wt_2,
                'wt_3'                          => $request->wt_3,
                'wt_4'                          => $request->wt_4,
                'wt_5'                          => $request->wt_5,
                'wt_6'                          => $request->wt_6,
                'wt_7'                          => $request->wt_7,
                'wt_8'                          => $request->wt_8,
                'remark'                            => 'created by system',
                'creator' => $detail->user_id,
            ];
            DB::transaction(function() use(&$post,$request,&$ticket_code,&$post_wt,$postDetail,$detail,&$post_log,&$post_log_wt) {
                $last = InspectionGoodsSubDetail::orderBy('id','desc')->lockForUpdate()->first();
                $ticket_code = $this->nextSequentialCode($last ? $last->inspection_sub_detail : null, 'SDINSG');
                $post['inspection_sub_detail'] = $ticket_code;
                $post_log['inspection_sub_detail'] = $ticket_code;
                $post_wt['sub_detail_inspection_code'] = $ticket_code;
                $post_log_wt['sub_detail_inspection_code'] = $ticket_code;

                InspectionGoodsSubDetail::create($post);
                InspectionGood::where('inspection_code', $detail->inspection_code)
                    ->where('status', '<', 1)
                    ->update(['status'    => 1]);
                WallThickness::create($post_wt);
                InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code_id)->update($postDetail);
                InspectionSubDetailLog::create($post_log);
                WallThicknessLog::create($post_log_wt);

            });
            return ResponseFormatter::success(
                $post,
                'Machine successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }
    }
    function mappingInspectionProgress(Request $request) {
        if (!$this->canViewShift($request->inspection_detail)) {
            return $this->forbiddenResponse();
        }

        $data = InspectionGoodsSubDetail::where('inspection_detail', $request->inspection_detail)->get();
       if($request->inspection_sub_detail == null){
           $detail = InspectionGoodsSubDetail::with([
               'machineRelation',
               'productRelation',
               'userRelation',
               'materialRelation',
               'inspectorRelation',

           ])->where('inspection_detail', $request->inspection_detail)->first();

       }else{
        $detail = InspectionGoodsSubDetail::with([
            'machineRelation',
            'productRelation',
            'userRelation',
            'materialRelation',
            'materialRelation',
            'inspectorRelation',

        ])->where('inspection_detail', $request->inspection_detail)->where('inspection_sub_detail', $request->inspection_sub_detail)->first();
       }
       if (!$detail) {
            return ResponseFormatter::error(null, 'No inspection progress found for this shift', 404);
       }
       $wt     = WallThickness::where('sub_detail_inspection_code',$detail->inspection_sub_detail)->get();
        return response()->json([
            'detail'=>$detail,
            'data'=>$data,
            'wt'=>$wt,
        ]);

    }
    function detailInspectionProgress(Request $request) {
        $subDetail = InspectionGoodsSubDetail::where('inspection_sub_detail', $request->inspection_sub_detail)->first();
        if ($subDetail && !$this->canViewShift($subDetail->inspection_detail)) {
            return $this->forbiddenResponse();
        }

        $detail = InspectionGoodsSubDetail::with([
            'machineRelation',
            'productRelation',
            'userRelation',
            'materialRelation',
            'inspectorRelation',

        ])->where('inspection_sub_detail', $request->inspection_sub_detail)->first();
        if (!$detail) {
            return ResponseFormatter::error(null, 'Inspection progress not found', 404);
        }
        $wt     = WallThickness::where('sub_detail_inspection_code',$request->inspection_sub_detail)->first();
        return response()->json([
            'detail'=>$detail,
            'wt'=>$wt ?: new \stdClass(),
        ]);
    }
    function lockInspectionGoods(Request $request) {
            // try {
         $request->validate(['detail_inspection_code' => 'required|string']);

         $detail = InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code)->first();
         if (!$detail) {
            return ResponseFormatter::error(null, 'Inspection shift transaction not found', 404);
         }
         // guard double-lock: dulu tiap panggilan status+1 tanpa cek
         if ((int) $detail->lock === 1 || (int) $detail->status !== 0) {
            return ResponseFormatter::error(null, 'This shift is already locked or completed', 400);
         }
         if (InspectionGoodsSubDetail::where('inspection_detail', $detail->detail_inspection_code)->count() === 0) {
            return ResponseFormatter::error(null, 'Add at least one inspection progress before locking', 400);
         }

         $header = InspectionGood::where('inspection_code',$detail->inspection_code)->first();
         if (!$header) {
            return ResponseFormatter::error(null, 'Inspection header not found', 404);
         }
         if (!$this->canAccessLocation($header->location_id)) {
            return $this->forbiddenResponse();
         }
         $approval = ApprovalHeader::where([
            'inspection_type'   =>  3,
            'shift'             => $detail->shift,
            'location'          =>  $header->location_id
        ])->first();
        if (!$approval) {
            return ResponseFormatter::error(null, 'Approval setting not found for this location and shift (inspection_type=3)', 400);
        }

        $approval_id = ApprovalDetail::where([
            'approval_code' => $approval->approval_code,
            'step'          => 1
        ])->first();
        if (!$approval_id) {
            return ResponseFormatter::error(null, 'Approval step 1 is not configured', 400);
        }
          $post = [
            'lock'    => 1,
            'status'    => 1,
            'approval_id'       => $approval_id->user_id
          ];
          $postUpdate =[
            'status'            => 1 ,

          ];
          $postApproval=[
            'transaction_code'  => $detail->detail_inspection_code,
            'status'            => 0,
            'user_id'           => $approval_id->user_id,
            'step'              => 1,
            'remark'            => 'Approval for inspection goods per shift',
            'inspection_type'   => 3,
            'type'              => 1
          ];


            DB::transaction(function() use($post,$postUpdate,$request,$postApproval,$detail) {
                InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code)->update($post);
                InspectionGoodsSubDetail::where('inspection_detail',$request->detail_inspection_code)->update($postUpdate);
                // header ikut masuk Approval Progress
                InspectionGood::where('inspection_code', $detail->inspection_code)
                    ->where('status', '<', 2)
                    ->update(['status' => 2]);
                ApprovalHistory::create($postApproval);
            });
            return ResponseFormatter::success(
                $post,
                'Inspection successfully locked, this is approval progress'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }
    }
    function getInspectionGoodSubDetailItem(Request $request) {
        if (!$this->canViewShift($request->inspection_detail)) {
            return $this->forbiddenResponse();
        }

        $data       = InspectionGoodsSubDetail::where('inspection_detail',$request->inspection_detail)->get();
        if ($data->isEmpty() && $request->inspection_sub_detail === null) {
            return response()->json(['data' => [], 'history' => [], 'history_wt' => []]);
        }
        $history    = InspectionSubDetailLog::with([
            'creatorRelation',
            'machineRelation',
            'productRelation',
            'materialRelation',
            'inspectorRelation',

            ])->where('inspection_sub_detail', $request->inspection_sub_detail === null ? $data[0]->inspection_sub_detail : $request->inspection_sub_detail)->orderBy('id','desc')->get();
        $history_wt    = WallThicknessLog::where('sub_detail_inspection_code', $request->inspection_sub_detail === null ? $data[0]->inspection_sub_detail : $request->inspection_sub_detail)->get();
        return response()->json([
            'data'=>$data,
            'history'=>$history,
            'history_wt'=>$history_wt,
        ]);
    }
    function updateInspectionGoodSubDetail(Request $request, UpdateInspectionGoodSubDetailRequest $updateInspectionGoodSubDetailRequest) {
        // try {
            $updateInspectionGoodSubDetailRequest->validated();

            $detail = InspectionGoodsSubDetail::where('inspection_sub_detail', $request->inspection_sub_detail_edit)->first();
            if (!$detail) {
                return ResponseFormatter::error(null, 'Inspection progress not found', 404);
            }
            if ($guard = $this->rejectIfShiftLocked($detail->inspection_detail)) {
                return $guard;
            }
            $product = MasterProduct::where('id', $request->product_edit)->first();
            $material = Material::where('id', $request->material_edit)->first();
            if (!$product || !$material) {
                return ResponseFormatter::error(null, 'Product or material not found', 404);
            }

            $post = [
                'product_id' => $product->id,
                'length_of_pipe' => $request->length_of_pipe_edit,
                'appearance_of_pipe' => $request->appearance_of_pipe_edit,
                'marking' => $request->marking_edit,
                'condition_pipe_of_cut' => $request->condition_pipe_of_cut_edit,
                'angle_pipe_of_spigot' => $request->angle_pipe_of_spigot_edit,
                'color_of_pipes' => $request->color_of_pipes_edit,
                'evaluation_spec' => $request->evaluation_spec_edit,
                'manufacturing_code' => $request->manufacturing_code_edit,
                'material_id' => $material->id,
                'tape_diameter' => $request->tape_diameter_edit,
                'machine_id' => $request->machine_id_edit,
                'inspector' => $request->inspector_edit,
            ];
            // $post_wt = [
            //     'wt_1' => $request->wt_1_edit,
            //     'wt_2' => $request->wt_2_edit,
            //     'wt_3' => $request->wt_3_edit,
            //     'wt_4' => $request->wt_4_edit,
            //     'wt_5' => $request->wt_5_edit,
            //     'wt_6' => $request->wt_6_edit,
            //     'wt_7' => $request->wt_7_edit,
            //     'wt_8' => $request->wt_8_edit,
            // ];

            $post_log = [
                'product_id' => $product->id,
                'length_of_pipe' => $request->length_of_pipe_edit,
                'appearance_of_pipe' => $request->appearance_of_pipe_edit,
                'marking' => $request->marking_edit,
                'condition_pipe_of_cut' => $request->condition_pipe_of_cut_edit,
                'angle_pipe_of_spigot' => $request->angle_pipe_of_spigot_edit,
                'color_of_pipes' => $request->color_of_pipes_edit,
                'evaluation_spec' => $request->evaluation_spec_edit,
                'manufacturing_code' => $request->manufacturing_code_edit,
                'material_id' => $material->id,
                'tape_diameter' => $request->tape_diameter_edit,
                'machine_id' => $request->machine_id_edit,
                'inspection_header' => $detail->inspection_header,
                'inspection_detail' => $request->inspection_detail_edit,
                'inspection_sub_detail' => $request->inspection_sub_detail_edit,
                'remark' => $request->reason,
                'creator' => auth()->user()->id,
                'user_id' => $detail->user_id,
                'time' => $detail->time,
                'shift' => $detail->shift,
                'status' => $detail->status,
                'inspector' => $request->inspector_edit,
            ];

            // $post_log_wt = [
            //     'inspection_code' => $detail->inspection_header,
            //     'detail_inspection_code' => $detail->inspection_detail,
            //     'sub_detail_inspection_code' => $request->inspection_sub_detail_edit,
            //     'wt_1' => $request->wt_1_edit,
        //     'wt_2' => $request->wt_2_edit,
            //     'wt_3' => $request->wt_3_edit,
            //     'wt_4' => $request->wt_4_edit,
            //     'wt_5' => $request->wt_5_edit,
            //     'wt_6' => $request->wt_6_edit,
            //     'wt_7' => $request->wt_7_edit,
            //     'wt_8' => $request->wt_8_edit,
            //     'remark' => $request->reason,
            //     'creator' => $detail->user_id,
            // ];
            $message = '';
            DB::transaction(function() use($post, $post_log, $request) {
                InspectionGoodsSubDetail::where('inspection_sub_detail', $request->inspection_sub_detail_edit)->update($post);
                InspectionSubDetailLog::create($post_log);
                // WallThickness::where('sub_detail_inspection_code', $request->inspection_sub_detail_edit)->update($post_wt);
                // WallThicknessLog::create($post_log_wt);
            });

            return ResponseFormatter::success(
                $post,
                'Machine successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th->getMessage(),
        //         'Machine failed to update',
        //         500
        //     );
        // }

    }

    function exportInspectionGoodProgress($x) {
        $detail_code = str_replace("_", "/", $x);
        $detail = InspectionGoodsDetail::where('detail_inspection_code', $detail_code)->first();
        if (!$detail) {
            abort(404, 'Inspection shift transaction not found');
        }
        if (!$this->canViewShift($detail_code)) {
            abort(403, 'You are not allowed to access this transaction');
        }
        $headers = InspectionGood::with(['locationRelation'])->where('inspection_code', $detail->inspection_code)->first();
        $data_report = InspectionGoodsSubDetail::with([
            'userRelation',
            'machineRelation',
            'wallThicknessRelation',
            'productRelation',
            'materialRelation',
            'headerRelation',
            'inspectorRelation',
            'headerRelation.locationRelation',
            'detailRelation',
        ])->where('inspection_detail', $detail_code)->get();
        $check_approval = ApprovalHistory::where('transaction_code', $detail_code)->first();
        $count_approval = ApprovalHistory::where('transaction_code', $detail_code)->count();
        $set_approval = ApprovalHistory::where('transaction_code', $detail_code)->get();
        $approval = '';
        if($check_approval !== null){
            // dulu lookup tanpa location -> bisa menampilkan daftar pengesah
            // lokasi lain yang kebetulan shift-nya sama
            $approvalHeader     = ApprovalHeader::where([
                'inspection_type'   => 3,
                'type'              => 1,
                'shift'             =>$detail->shift,
                'location'          =>$headers->location_id,
            ])->first();
            if ($approvalHeader) {
                $approval = ApprovalDetail::with([
                    'userRelation',
                ])->where('approval_code', $approvalHeader->approval_code)->get();
            }
        }

        $data =[
            'headers'   => $headers,
            'data'   => $data_report,
            'detail'   => $detail,
            'approval'   => $approval,
            'count_approval'   => $count_approval,
            'set_approval'   => $set_approval,
        ];
        $cetak              = view('report.inspection.report_inspection_goods', $data);
        $imageLogo          = '<img src="'.public_path('icon.png').'" width="70px" style="float: right;"/>';
        $header             = '';
        $header             .= '<table width="100%">
                                    <tr>
                                        <td style="padding-left:10px;">
                                            <span style="font-size: 16px; font-weight: bold;"> PT PRALON</span>
                                            <br>
                                            <span style="font-size:9px;">Synergy Building #08-08 Tangerang 15143 - Indonesia +62 21 304 38808</span>
                                        </td>
                                        <td style="width:33%"></td>
                                            <td style="width: 50px; text-align:right;">'.$imageLogo.'
                                        </td>
                                    </tr>

                                </table>
                                <hr>';

                                $footer             = '<hr>
                                <table width="100%" style="font-size: 8px;margin-top:-5px">
                                <tr>
                                <td width="90%" align="left"><b>Disclaimer</b><br>this document is strictly private, confidential and personal to recipients and should not be copied, distributed or reproduced in whole or in part, not passed to any third party.</td>
                                        <td width="10%" style="text-align: right;"> {PAGENO}</td>
                                    </tr>
                                </table>';


            $mpdf           = new PDF();
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage(
                'P', // L - landscape, P - portrait
                '',
                '',
                '',
                '',
                2, // margin_left
                2, // margin right
                18, // margin top
                18, // margin bottom
                2, // margin header
                0
            ); // margin footer
            $mpdf->WriteHTML($cetak);
            // Output a PDF file directly to the browser
            ob_clean();
            $mpdf->Output('LIMS_Inspection_Goods_' . str_replace('/', '_', $detail_code) . '_' . date('Ymd_Hi') . '.pdf', 'I');
    }
    function getWallThickness(Request $request) {
        if (!$this->canViewShift($request->detail_inspection_code)) {
            return $this->forbiddenResponse();
        }

        $subdetail = InspectionGoodsSubDetail::where('inspection_detail', $request->detail_inspection_code)->get();
        if ($subdetail->isEmpty() && $request->inspection_sub_detail == null) {
            return response()->json(['subdetail' => [], 'data' => []]);
        }
        $data = WallThickness::where('sub_detail_inspection_code', $request->inspection_sub_detail == null ? $subdetail[0]->inspection_sub_detail : $request->inspection_sub_detail)->get();
        return response()->json([
            'subdetail' => $subdetail,
            'data' => $data,
        ]);
    }
    /**
     * Shift yang sudah di-lock (menunggu/menyelesaikan approval) = read-only.
     * UI memang menyembunyikan tombolnya, tapi endpoint harus ikut menolak.
     */
    private function rejectIfShiftLocked($detailInspectionCode) {
        $shift = InspectionGoodsDetail::where('detail_inspection_code', $detailInspectionCode)->first();
        if (!$shift) {
            return null;
        }
        $header = InspectionGood::where('inspection_code', $shift->inspection_code)->first();
        if ($header && !$this->canAccessLocation($header->location_id)) {
            return $this->forbiddenResponse();
        }
        if ((int) $shift->lock === 1) {
            return ResponseFormatter::error(null, 'This shift is locked and cannot be modified', 400);
        }
        if ((int) $shift->status === 2) {
            return ResponseFormatter::error(null, 'This shift is completed and cannot be modified', 400);
        }

        return null;
    }

    function updateWallThickness(Request $request) {
        $detail = WallThickness::find($request->id);
        if (!$detail) {
            return ResponseFormatter::error(null, 'Wall thickness record not found', 404);
        }
        if ($guard = $this->rejectIfShiftLocked($detail->detail_inspection_code)) {
            return $guard;
        }

        $post =[
            'wt_1'    => $request->dynamic_wt_1,
            'wt_2'    => $request->dynamic_wt_2,
            'wt_3'    => $request->dynamic_wt_3,
            'wt_4'    => $request->dynamic_wt_4,
            'wt_5'    => $request->dynamic_wt_5,
            'wt_6'    => $request->dynamic_wt_6,
            'wt_7'    => $request->dynamic_wt_7,
            'wt_8'    => $request->dynamic_wt_8,
        ];
        $post_log = [
            'inspection_code'   => $detail->inspection_code,
            'detail_inspection_code'   => $detail->detail_inspection_code,
            'sub_detail_inspection_code'   => $detail->sub_detail_inspection_code,
            'wt_1'    => $request->dynamic_wt_1,
            'wt_2'    => $request->dynamic_wt_2,
            'wt_3'    => $request->dynamic_wt_3,
            'wt_4'    => $request->dynamic_wt_4,
            'wt_5'    => $request->dynamic_wt_5,
            'wt_6'    => $request->dynamic_wt_6,
            'wt_7'    => $request->dynamic_wt_7,
            'wt_8'    => $request->dynamic_wt_8,
            'creator'   => auth()->user()->id,
            'remark'    => 'updated'
        ];
           DB::transaction(function() use($post,$request, $post_log,$detail) {

                $detail->update($post);
                WallThicknessLog::create($post_log);

           });
           return ResponseFormatter::success(
               $post,
               'Wall Thickness successfully updated'
           );
       // } catch (\Throwable $th) {
       //     return ResponseFormatter::error(
       //         $th,
       //         'Machine failed to update',
       //         500
       //     );
       // }
    }
    function addWallThickness(Request $request) {
        if ($guard = $this->rejectIfShiftLocked($request->detail_inspection_code)) {
            return $guard;
        }
        $validation =  WallThickness::where('detail_inspection_code',$request->detail_inspection_code)->where('sub_detail_inspection_code',$request->inspection_sub_detail)->latest('id')->count();
        if($validation < 3){

            $detail = WallThickness::where('detail_inspection_code',$request->detail_inspection_code)->where('sub_detail_inspection_code',$request->inspection_sub_detail)->latest('id')->first();
            if (!$detail) {
                return ResponseFormatter::error(null, 'No existing wall thickness row for this inspection', 400);
            }
            $post =[
                'inspection_code'   => $detail->inspection_code,
                'step'              =>$detail->step + 1,
                'detail_inspection_code'   => $detail->detail_inspection_code,
                'sub_detail_inspection_code'   => $detail->sub_detail_inspection_code,
                'wt_1'    => $request->update_wt_1,
                'wt_2'    => $request->update_wt_2,
                'wt_3'    => $request->update_wt_3,
                'wt_4'    => $request->update_wt_4,
                'wt_5'    => $request->update_wt_5,
                'wt_6'    => $request->update_wt_6,
                'wt_7'    => $request->update_wt_7,
                'wt_8'    => $request->update_wt_8,
            ];
            $post_log = [
                'inspection_code'   => $detail->inspection_code,
                'step'              =>$detail->step + 1,
                'detail_inspection_code'   => $detail->detail_inspection_code,
                'sub_detail_inspection_code'   => $detail->sub_detail_inspection_code,
                'wt_1'    => $request->update_wt_1,
                'wt_2'    => $request->update_wt_2,
                'wt_3'    => $request->update_wt_3,
                'wt_4'    => $request->update_wt_4,
                'wt_5'    => $request->update_wt_5,
                'wt_6'    => $request->update_wt_6,
                'wt_7'    => $request->update_wt_7,
                'wt_8'    => $request->update_wt_8,
                'creator'   => auth()->user()->id,
                'remark'    => 'has craeted new data'
            ];


               DB::transaction(function() use($post,$request, $post_log,$detail) {
                 WallThickness::create($post);
                 WallThicknessLog::create($post_log);

               });
               return ResponseFormatter::success(
                   $post,
                   'Wall Thickness successfully updated'
               );
        }else{
            $post = '';
            return ResponseFormatter::error(
                $post,
                'Wall Thickness step is more than 3'
            );
        }
       // } catch (\Throwable $th) {
       //     return ResponseFormatter::error(
       //         $th,
       //         'Machine failed to update',
       //         500
       //     );
       // }
    }
}
