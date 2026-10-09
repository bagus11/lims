<?php

namespace App\Http\Controllers\Transaction\Inspection;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Inspection\Material\AddInspectionMaterialRequest;
use App\Models\Master\Supplier;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Material\Pellet;
use App\Models\Transaction\Inspection\Material\ResinDetail;
use App\Models\Transaction\Inspection\Material\ResinModel;
use App\Models\Transaction\Material\InspectionMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumConvert;
use \Mpdf\Mpdf as PDF;

class InspectionMaterialController extends Controller
{
    function  index() {
        return view('transaction.inspection.material.inspection_material-index');
    }

    /**
     * Non-GM hanya boleh menyentuh transaksi lokasinya sendiri.
     * (kolom lokasi di tabel ini bernama `location`, bukan location_id)
     */
    private function canAccessLocation($locationId): bool {
        if (auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            return true;
        }

        return (string) $locationId === (string) auth()->user()->kode_kantor;
    }

    /**
     * Akses baca: lokasi sendiri, GM, atau approver yang pernah ditugaskan.
     */
    private function canViewMaterial(InspectionMaterial $header): bool {
        if ($this->canAccessLocation($header->location)) {
            return true;
        }

        return ApprovalHistory::where('transaction_code', $header->inspection_code)
            ->where('inspection_type', 2)
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

    function getInspectionMaterial(Request $request) {
        $query = InspectionMaterial::with([
            'locationRelation',
            'inspectorRelation',
            'materialRelation',
            // 'supplierRelation',
            'userRelation',
        ]);

        // sebelumnya listing ini tanpa scoping sama sekali: semua user
        // melihat seluruh transaksi semua lokasi
        if (!auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            $query->where('location', auth()->user()->kode_kantor);
        }

        if ($request->from && $request->to) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to]);
        }

        return response()->json([
            'data'=>$query->orderBy('id', 'desc')->get()
        ]);
    }
    function getSupplier() {
        $data = Supplier::where('status',1)
        ->get();
        return response()->json([
            'data'=>$data
        ]);
    }
    function addInspectionMaterial(Request $request, AddInspectionMaterialRequest $addInspectionMaterialRequest) {
        // try{
        $addInspectionMaterialRequest->validated();
        // Non-GM tidak boleh membuat transaksi atas nama lokasi lain
        $locationId = $request->location;
        if (!auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            $locationId = auth()->user()->kode_kantor;
        }

        // inspection_code diisi di dalam transaksi (lihat bawah) supaya dua
        // request bersamaan tidak menghasilkan kode kembar
        $ticket_code = null;

        $post =[
            'inspection_code'   =>$ticket_code,
            'location'          =>$locationId,
            'user_id'           => auth()->user()->id,
            'status'            => 0,
            'inspector'         =>$request->inspector,
            'type'              =>$request->type,
            'product_id'        =>$request->material,
            'supplier'          =>$request->supplier,
            'merk'              =>$request->brand,
            'amount'            =>$request->amount,
            'weight'            =>$request->weight,
            'standard'          =>$request->standard,
            'no_lot'            =>$request->no_lot,
            'certificate'       =>$request->certificate,
            'package'           =>0,
            'approval_id'       =>0,
            'lock'              =>0,
            'no_pof'            =>'',
            'remark'            =>''
        ];
        $post_pellet=[
            'inspection_code'       =>$ticket_code,
            'visible_properties'    =>'',
            'packaging'    =>'',
            'amount_per_sak'    =>0,
            'weight_per_sak'    =>0,
            'produsen_id'    =>$request->produsen_id,
            'mfr'    =>0,
        ];
        $step = 0;
        if($request->amount <= 50 ){
            $step = 6;
        }else if($request->amount >= 51 && $request->amount <=100){
            $step = 8;
        }else if($request->amount >= 101 && $request->amount <= 150){
            $step = 13;
        }else if($request->amount >= 151){
            $step = 20;
        }
        $post_resin =[
            'inspection_code'   =>$ticket_code,
            'color'   =>'',
            'visible_properties'   =>'',
            'packaging'   =>'',
            'particle_size'   =>'',
            'step'   =>$step,
            'volatile_matter'   =>0,
            'amount'   =>0,
            'weight'   =>0,

        ];
        DB::transaction(function() use(&$post,$request,&$post_resin,&$ticket_code,$step,&$post_pellet) {
            // lock baris terakhir lalu generate kode di dalam transaksi yang
            // sama dengan insert — anti kode kembar saat request bersamaan
            $last = InspectionMaterial::orderBy('id', 'desc')->lockForUpdate()->first();
            $ticket_code = $this->nextSequentialCode($last ? $last->inspection_code : null, 'INSM');
            $post['inspection_code'] = $ticket_code;
            $post_resin['inspection_code'] = $ticket_code;
            $post_pellet['inspection_code'] = $ticket_code;

            InspectionMaterial::create($post);
            if($request->type == 1){
                ResinModel::create($post_resin);
                for($i = 0 ; $i < $step ; $i ++){
                    ResinDetail::create([
                        'inspection_code'   =>$ticket_code,
                        'step'              => $i + 1,
                        'value'             => 0
                    ]);
                }
            }else if($request->type == 2){
                Pellet::create($post_pellet);
            }

        });
        return ResponseFormatter::success(
            $post,
            'Inspection Material successfully updated'
        );
    // } catch (\Throwable $th) {
    //     return ResponseFormatter::error(
    //         $th,
    //         'Machine failed to update',
    //         500
    //     );
    // }


    }
    function getResinInspection(Request $request){
        $data = '';
        $min = '';
        $max = '';
        $avg = '';
        $detail = InspectionMaterial::with([
            'locationRelation',
            'materialRelation',
            'inspectorRelation',
            'supplierRelation',
        ])->where('inspection_code', $request->inspection_code)->first();

        if (!$detail) {
            return ResponseFormatter::error(null, 'Inspection Material transaction not found', 404);
        }
        if (!$this->canViewMaterial($detail)) {
            return $this->forbiddenResponse();
        }

        if($detail->type == 2){
            $header =Pellet::where('inspection_code', $request->inspection_code)->first();
        }else{
            $data = ResinDetail::where('inspection_code', $request->inspection_code)->get();
            $header = ResinModel::where('inspection_code',$request->inspection_code)->first();
            $min = ResinDetail::where('inspection_code', $request->inspection_code)->orderBy('value','asc')->first();
            $max = ResinDetail::where('inspection_code', $request->inspection_code)->orderBy('value','desc')->first();
            $avg = ResinDetail::where('inspection_code', $request->inspection_code)->avg('value');
        }

        return response()->json([
            'data'=>$data,
            'header'=>$header,
            'detail'=>$detail,
            'min'=>$min,
            'max'=>$max,
            'avg'=>$avg,
        ]);
    }
    function getPelletInspection(Request $request) {
        $detail = InspectionMaterial::with([
            'locationRelation',
            'materialRelation',
            'inspectorRelation',
            'supplierRelation',

        ])->where('inspection_code', $request->inspection_code)->first();

        if (!$detail) {
            return ResponseFormatter::error(null, 'Inspection Material transaction not found', 404);
        }
        if (!$this->canViewMaterial($detail)) {
            return $this->forbiddenResponse();
        }

        $header =Pellet::with([
            'produsenRelation'
        ])->
        where('inspection_code', $request->inspection_code)->first();
        if (!$header) {
            return ResponseFormatter::error(null, 'Pellet detail record not found', 404);
        }
        return response()->json([
            'header'=>$header,
            'detail'=>$detail,
        ]);
    }
    /**
     * Transaksi yang sudah lock (menunggu approval) atau Done = read-only.
     * UI menyembunyikan tombol, server wajib ikut menolak.
     */
    private function rejectIfNotEditable($inspectionCode) {
        $header = InspectionMaterial::where('inspection_code', $inspectionCode)->first();
        if (!$header) {
            return ResponseFormatter::error(null, 'Inspection Material transaction not found', 404);
        }
        if (!$this->canAccessLocation($header->location)) {
            return $this->forbiddenResponse();
        }
        if ((int) $header->lock === 1 || !in_array((int) $header->status, [0, 1, 4], true)) {
            return ResponseFormatter::error(null, 'This transaction is locked and cannot be modified', 400);
        }

        return null;
    }

    function updateResinDetail(Request $request) {
            // try{
                $request->validate([
                    'inspection_code' => 'required|string',
                    'step' => 'required|integer|min:1',
                    'value' => 'required|numeric|between:0,999999.999999',
                ]);
                if ($guard = $this->rejectIfNotEditable($request->inspection_code)) {
                    return $guard;
                }
                $post = [
                    'value' => $request->value
                ];
                ResinDetail::where([
                    'inspection_code'   => $request->inspection_code,
                    'step'   => $request->step,
                ])->update($post);
                return ResponseFormatter::success(
                    $post,
                    'Resin on step '.$request->step.' successfully updated'
                );
            // } catch (\Throwable $th) {
            //     return ResponseFormatter::error(
            //         $th,
            //         'Machine failed to update',
            //         500
            //     );
            // }
    }
    function updateResinProperties(Request $request) {
           // try{
            $request->validate([
                'inspection_code' => 'required|string',
                'resin_volatile_matter' => 'nullable|numeric',
                'resin_amount_per_sak' => 'nullable|numeric',
                'resin_weight_per_sak' => 'nullable|numeric',
            ]);
            if ($guard = $this->rejectIfNotEditable($request->inspection_code)) {
                return $guard;
            }
            $post = [
                'visible_properties' => $request->select_vivsible_properties,
                'packaging' => $request->select_resin_package,
                'color' => $request->resin_color,
                'particle_size' => $request->resin_particle_size,
                'volatile_matter' => $request->resin_volatile_matter,
                'amount' => $request->resin_amount_per_sak,
                'weight' => $request->resin_weight_per_sak,

            ];

            $post_header = [
                'no_pof' => $request->resin_pof_number,
                'remark' => $request->resin_remark,
            ];
            DB::transaction(function() use($post,$request,$post_header) {
                $head = InspectionMaterial::where('inspection_code', $request->inspection_code)->first();
                $head->status == 0 ? InspectionMaterial::where('inspection_code', $request->inspection_code)->update(['status' => $head->status + 1])  :'';
                ResinModel::where([
                    'inspection_code'   => $request->inspection_code,
                ])->update($post);
                InspectionMaterial::where('inspection_code', $request->inspection_code)->update($post_header);
            });
            return ResponseFormatter::success(
                $post,
                'Resin Properties successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }
    }
    function updatePelletProperties(Request $request) {
          // try{
            $request->validate([
                'inspection_code' => 'required|string',
                'pellet_amount_per_sak' => 'nullable|numeric',
                'pellet_weight_per_sak' => 'nullable|numeric',
                'pelet_mfr' => 'nullable|numeric',
            ]);
            if ($guard = $this->rejectIfNotEditable($request->inspection_code)) {
                return $guard;
            }
            $post = [
                'visible_properties' => $request->select_pellet_visible_properties,
                'packaging' => $request->select_pellet_packaging,
                'amount_per_sak' => $request->pellet_amount_per_sak,
                'weight_per_sak' => $request->pellet_weight_per_sak,
                'mfr'           => $request->pelet_mfr
            ];
            $post_header = [
                'no_pof' => $request->pellet_pof_number,
                'remark' => $request->pellet_remark,
            ];
            DB::transaction(function() use($post,$request,$post_header) {
                $head = InspectionMaterial::where('inspection_code', $request->inspection_code)->first();
                $head->status == 0 ? InspectionMaterial::where('inspection_code', $request->inspection_code)->update(['status' => $head->status + 1])  :'';
                Pellet::where([
                    'inspection_code'   => $request->inspection_code,
                ])->update($post);
                InspectionMaterial::where('inspection_code', $request->inspection_code)->update($post_header);
            });


            return ResponseFormatter::success(
                $post,
                'Resin on step '.$request->step.' successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }
    }
    function lockInspectionMaterial(Request $request) {
               // try{
                $request->validate(['inspection_code' => 'required|string']);

                $header = InspectionMaterial::where('inspection_code',$request->inspection_code)->first();
                if (!$header) {
                    return ResponseFormatter::error(null, 'Inspection Material transaction not found', 404);
                }
                // guard double-lock: dulu tiap panggilan status+1 — dua klik
                // beruntun = DONE tanpa approval
                if (!$this->canAccessLocation($header->location)) {
                    return $this->forbiddenResponse();
                }
                if ((int) $header->lock === 1 || !in_array((int) $header->status, [1, 4], true)) {
                    return ResponseFormatter::error(null, 'Only an unlocked In Progress or Rejected transaction can be locked', 400);
                }

                $approval = ApprovalHeader::where([
                    'inspection_type'   =>  2,
                    'shift'             => 1,
                    'location'          =>  $header->location
                ])->first();
                if (!$approval) {
                    return ResponseFormatter::error(null, 'Approval setting not found for this location (inspection_type=2)', 400);
                }
                $approval_id = ApprovalDetail::where([
                    'approval_code'     => $approval->approval_code,
                    'step'              => 1
                ])->first();
                if (!$approval_id) {
                    return ResponseFormatter::error(null, 'Approval step 1 is not configured', 400);
                }
                $post = [
                    'lock'              => 1,
                    'status'            => 2,
                    'approval_id'       => $approval_id->user_id
                ];
                $postApproval=[
                    'transaction_code'  => $header->inspection_code,
                    'status'            => 0,
                    'user_id'           => $approval_id->user_id,
                    'step'              => 1,
                    'remark'            => 'Approval for inspection material per shift',
                    'inspection_type'   => 2,
                    'type'              => 1
                ];
                    DB::transaction(function() use($post,$request,$postApproval) {
                        InspectionMaterial::where('inspection_code',$request->inspection_code)->update($post);
                        ApprovalHistory::create($postApproval);
                    });
                return ResponseFormatter::success(
                    $post,
                    'Inspection Material Successfully locked'
                );
            // } catch (\Throwable $th) {
            //     return ResponseFormatter::error(
            //         $th,
            //         'Machine failed to update',
            //         500
            //     );
            // }
    }

    function exportInspectionMaterials($id) {
        $inspection_code =str_replace("_", "/", $id);
        $steps = [];
        $values = [];
        $chartUrl = null;
        $data = '';
        $min = '';
        $max = '';
        $avg = '';
        $orientation = '';
        $name = '';
       $detail = InspectionMaterial::with([
            'locationRelation',
            'materialRelation',
            'inspectorRelation',
            'supplierRelation',
            'productRelation',
        ])->where('inspection_code', $inspection_code)->first();

        if (!$detail) {
            abort(404, 'Inspection not found');
        }
        if (!$this->canViewMaterial($detail)) {
            abort(403, 'You are not allowed to access this transaction');
        }

        $approval = ApprovalHistory::with('userRelation')->where('transaction_code', $detail->inspection_code)->get();

        if($detail->type == 2){
            $header =Pellet::with('produsenRelation')->where('inspection_code', $inspection_code)->first();
           $data = [
                'detail'   => $detail,
                'header'   => $header,
                'approval'   => $approval,
            ];

        }else{
            $data = ResinDetail::where('inspection_code', $inspection_code)->get();
            $header = ResinModel::where('inspection_code',$inspection_code)->first();
            $min = ResinDetail::where('inspection_code', $inspection_code)->orderBy('value','asc')->first();
            $max = ResinDetail::where('inspection_code', $inspection_code)->orderBy('value','desc')->first();
            $avg = ResinDetail::where('inspection_code', $inspection_code)->avg('value');
            $steps = [];
            $values = [];

            foreach ($data as $d) {
                $steps[] = $d->step;
                $values[] = $d->value;
            }
            // grafik kini digambar lokal (SVG) di blade report — tidak lagi
            // memakai quickchart.io (butuh internet + data QC keluar)
            $chartUrl = null;
            $data = [
                    'detail'   => $detail,
                    'header'   => $header,
                    'data'     => $data,
                    'min'      => $min,
                    'max'      => $max,
                    'avg'      => $avg,
                    'chartUrl' => $chartUrl,
                    'approval'   => $approval,
                ];
        }

        if($detail->type == 1){
            $orientation = 'P';
            $name = 'Inspection Resin Material Report';
            $cetak              = view('report.inspection.report-resin_material', $data);
        }else{
            $orientation = 'L';
            $name = 'Inspection Pellet Material Report';
            $cetak              = view('report.inspection.report-pellet_material', $data);
        }
            $imageLogo          = '<img src="'.public_path('icon.png').'" width="70px" style="float: right;"/>';
            $header             = '';
            $header             .= '<table width="100%" >
                                        <tr>
                                        <td style="border:none;padding-left:10px;">
                                            '.$imageLogo.'
                                            <br>
                                            <span style="font-size:9px;">'.$detail->locationRelation->address.'</span>
                                        </td>

                                    </tr>

                                </table>
                                <hr>';

                                $footer             = '<hr>
                                <table width="100%" style="font-size: 8px;margin-top:-5px">
                                <tr>
                                        <td style="border:none;" width="90%" align="left"><b>Disclaimer</b><br>this document is strictly private, confidential and personal to recipients and should not be copied, distributed or reproduced in whole or in part, not passed to any third party.</td>
                                        <td width="10%" style="text-align: right;border:none;"> {PAGENO}</td>
                                    </tr>
                                </table>';


            $mpdf           = new PDF();
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage(
              $orientation, // L - landscape, P - portrait
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
            $mpdf->Output($name.' ('.date('Y-m-d').').pdf', 'I');
    }
}
