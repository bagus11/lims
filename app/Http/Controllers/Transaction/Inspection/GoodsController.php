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

        // GM: bisa melihat semua location
        if (auth()->user()->hasPermissionTo('get-only_gm-inspection-goods')) {
            $query->without('machineRelation');
        } else {
            // User biasa: hanya bisa melihat location sendiri
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
            $increment_code= InspectionGood::orderBy('id','desc')->first();
            $date_month =strtotime(date('Y-m-d'));
            $month =idate('m', $date_month);
            $year = idate('y', $date_month);
            $month_convert =  NumConvert::roman($month);
            if($increment_code ==null){
                $ticket_code = '1/INSG/'.$month_convert.'/'.$year;
            }else{
                $month_before = explode('/',$increment_code->inspection_code,-1);
                if($month_convert != $month_before[2]){
                    $ticket_code = '1/INSG/'.$month_convert.'/'.$year;
                }else{
                    $ticket_code = $month_before[0] + 1 .'/INSG/'.$month_convert.'/'.$year;
                }
            }

            $post = [
                'inspection_code'   => $ticket_code,
                'user_id'           =>auth()->user()->id,
                'location_id'       =>$request->location_id,
                // 'machine_id'       =>$request->machine_id,
                'current_approval'  => 0,
                'status'  =>0,
            ];
            DB::transaction(function() use($post,$request,$ticket_code) {

                $detail_ticket = '';

                InspectionGood::create($post);
                for($i = 0 ; $i < 3 ; $i++){
                    $increment_code_detail= InspectionGoodsDetail::orderBy('id','desc')->first();
                    $date_month_detail =strtotime(date('Y-m-d'));
                    $month_detail =idate('m', $date_month_detail);
                    $year_detail = idate('y', $date_month_detail);
                    $month_convert_detail =  NumConvert::roman($month_detail);
                    if($increment_code_detail ==null){
                        $detail_ticket = '1/DINSG/'.$month_convert_detail.'/'.$year_detail;
                    }else{
                        $month_before = explode('/',$increment_code_detail->detail_inspection_code,-1);
                        if($month_convert_detail != $month_before[2]){
                            $detail_ticket = '1/DINSG/'.$month_convert_detail.'/'.$year_detail;
                        }else{
                            $detail_ticket = $month_before[0] + 1 .'/DINSG/'.$month_convert_detail.'/'.$year_detail;
                        }
                    }
                    $post_log = [
                        'inspection_code'   =>$ticket_code,
                        'detail_inspection_code'   =>$detail_ticket,
                        'shift' =>$i + 1,
                        'user_id'=>auth()->user()->id,
                        'status'    => 0,
                        'approval_id'    => 0,
                        'evaluation'=>''
                    ];
                    InspectionGoodsDetail ::create($post_log);
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
            $increment_code= InspectionGoodsSubDetail::orderBy('id','desc')->first();
            $date_month =strtotime(date('Y-m-d'));
            $detail = InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code_id)->first();
            $month =idate('m', $date_month);
            $year = idate('y', $date_month);
            $month_convert =  NumConvert::roman($month);
            // dd($increment_code->inspection_sub_detail);
            if($increment_code ==null){
                $ticket_code = '1/SDINSG/'.$month_convert.'/'.$year;
            }else{
                $month_before = explode('/',$increment_code->inspection_sub_detail,-1);
                if($month_convert != $month_before[2]){
                    $ticket_code = '1/SDINSG/'.$month_convert.'/'.$year;
                }else{
                    $ticket_code = $month_before[0] + 1 .'/SDINSG/'.$month_convert.'/'.$year;
                }
            }
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
            DB::transaction(function() use($post,$request,$ticket_code,$post_wt,$postDetail,$detail,$post_log,$post_log_wt) {
                InspectionGoodsSubDetail::create($post);
                InspectionGood::where('inspection_code', $detail->inspection_code)->update(['status'    => 1]);
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
       $wt     = WallThickness::where('sub_detail_inspection_code',$detail->inspection_sub_detail)->get();
        return response()->json([
            'detail'=>$detail,
            'data'=>$data,
            'wt'=>$wt,
        ]);

    }
    function detailInspectionProgress(Request $request) {
        $detail = InspectionGoodsSubDetail::with([
            'machineRelation',
            'productRelation',
            'userRelation',
            'materialRelation',
            'inspectorRelation',

        ])->where('inspection_sub_detail', $request->inspection_sub_detail)->first();
        $wt     = WallThickness::where('sub_detail_inspection_code',$request->inspection_sub_detail)->first();
        return response()->json([
            'detail'=>$detail,
            'wt'=>$wt,
        ]);
    }
    function lockInspectionGoods(Request $request) {
            // try {
         $detail = InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code)->first();
         $header = InspectionGood::where('inspection_code',$detail->inspection_code)->first();
         $approval = ApprovalHeader::where([
            'inspection_type'   =>  3,
            'shift'             => $detail->shift,
            'location'          =>  $header->location_id
        ])->first();

        $approval_id = ApprovalDetail::where([
            'approval_code' => $approval->approval_code,
            'step'          => 1
        ])->first();
          $post = [
            'lock'    => 1,
            'status'    => $detail->status + 1,
            'approval_id'       => $approval_id->user_id
          ];
        //   dd($approval_id);
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


            DB::transaction(function() use($post,$postUpdate,$request,$postApproval) {
                InspectionGoodsDetail::where('detail_inspection_code',$request->detail_inspection_code)->update($post);
                InspectionGoodsSubDetail::where('inspection_detail',$request->detail_inspection_code)->update($postUpdate);
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
        $data       = InspectionGoodsSubDetail::where('inspection_detail',$request->inspection_detail)->get();
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
            $product = MasterProduct::where('id', $request->product_edit)->first();
            $material = Material::where('id', $request->material_edit)->first();

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
            $approvalHeader     = ApprovalHeader::where([
                'inspection_type'   => 3,
                'type'              => 1,
                'shift'             =>$detail->shift
            ])->first();
            $approval = ApprovalDetail::with([
                'userRelation',

            ])->where('approval_code', $approvalHeader->approval_code)->get();

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
            $mpdf->Output('Report Stock'.'('.date('Y-m-d').').pdf', 'I');
    }
    function getWallThickness(Request $request) {

        $subdetail = InspectionGoodsSubDetail::where('inspection_detail', $request->detail_inspection_code)->get();
        $data = WallThickness::where('sub_detail_inspection_code', $request->inspection_sub_detail == null ? $subdetail[0]->inspection_sub_detail : $request->inspection_sub_detail)->get();
        return response()->json([
            'subdetail' => $subdetail,
            'data' => $data,
        ]);
    }
    function updateWallThickness(Request $request) {
        $detail = WallThickness::find($request->id);

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
        $validation =  WallThickness::where('detail_inspection_code',$request->detail_inspection_code)->where('sub_detail_inspection_code',$request->inspection_sub_detail)->latest('id')->count();
        if($validation < 3){

            $detail = WallThickness::where('detail_inspection_code',$request->detail_inspection_code)->where('sub_detail_inspection_code',$request->inspection_sub_detail)->latest('id')->first();
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
