<?php

namespace App\Http\Controllers\Setting;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Approval\StoreApprovalHeaderRequest;
use App\Http\Requests\Setting\Approval\UpdateApprovalRequest;
use App\Http\Requests\Setting\Approval\UpdateInspectionGoodsApprovalRequest;
use App\Models\Master\MasterLocation;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\InspectionGoodsDetail;
use App\Models\Transaction\Insspection\InspectionGood;
use App\Models\Transaction\Material\InspectionMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;
use NumConvert;
class ApprovalController extends Controller
{
    function index() {
        return view('setting.approval.approval-index');
    }
    function getApprovalHeader() {
        $data = ApprovalHeader::with([
            'locationRelation'
        ])->get();
        return response()->json([
            'data'=>$data,
        ]);
    }

    function addApprovalHeader(Request $request, StoreApprovalHeaderRequest $storeApprovalHeaderRequest) {
        // try {
            $storeApprovalHeaderRequest->validated();
            $increment_code= ApprovalHeader::orderBy('id','desc')->first();
            $date_month =strtotime(date('Y-m-d'));
            $location = MasterLocation::find($request->location);
            $month =idate('m', $date_month);
            $year = idate('y', $date_month);
            $month_convert =  NumConvert::roman($month);

            if($increment_code ==null){
                $ticket_code = '1/'.$location->initial.'/'.$request->inspection_type.'/'.$month_convert.'/'.$year;
            }else{
                $month_before = explode('/',$increment_code->approval_code,-1);
                // dd($month_convert != $month_before[3]);
                if($month_convert != $month_before[3]){
                    $ticket_code = '1/'.$location->initial.'/'.$request->inspection_type.'/'.$month_convert.'/'.$year;
                }else{
                    $ticket_code = $month_before[0] + 1 .'/'.$location->initial.'/'.$request->inspection_type.'/'.$month_convert.'/'.$year;
                }
            }
            // dd($ticket_code);
            $post =[
                'approval_code' => $ticket_code,
                'location'=>$request->location,
                'inspection_type'=>$request->inspection_type,
                'step'=>$request->step,
                'shift'=>$request->shift,
                'type'=>$request->type,
                'status'   => 1
            ];
            ApprovalHeader::create($post);
            return ResponseFormatter::success(
               $post,
                'Menus successfully added'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Menus failed to update',
        //         500
        //     );
        // }
    }
    function getStepApproval(Request $request) {
        $data = ApprovalDetail::with('userRelation')->where('approval_code',$request->approval_code)->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getApprovalList(Request $request) {
        $header = ApprovalHeader::where([
            'inspection_type'   =>  $request->inspection_type,
            'location'          =>  $request->location,
            'shift'          =>  $request->shift,
        ])->first();
        $data = ApprovalDetail::with([
            'userRelation'
        ])->where('approval_code', $header->approval_code)->get();
        $approvalHistory= ApprovalHistory::with([
            'userRelation'
        ])->where('transaction_code', $request->detail)->get();

        return response()->json([
            'data'      => $data,
            'history'   => $approvalHistory
        ]);
    }
    function getApprovalUser(Request $request) {
        $data = ApprovalHistory::where('user_id', auth()->user()->id)->where('status',0)->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function updateApproval(Request $request, UpdateApprovalRequest $updateApprovalRequest) {
        // try {
            $updateApprovalRequest->validated();
            $validating = ApprovalHeader::where('approval_code',$request->approval_id)->count();
            $header = ApprovalHeader::where('approval_code',$request->approval_id)->first();
            $array_post=[];
            foreach($request->user_array as $row){
                $location = ApprovalDetail::where('approval_code',$request->approval_id)->first();
                $post = [
                    'user_id'           => $row['user_id'],
                    'step'              => $row['step'],
                    'approval_code'     =>$request->approval_id,
                    'inspection_type'   =>$header->inspection_type,
                    'created_at'        =>date('Y-m-d H:i:s')
                ];
                array_push($array_post, $post);
            }
            DB::transaction(function() use($validating,$array_post,$request) {
                if($validating > 0){
                    ApprovalDetail::where('approval_code',$request->approval_id)->delete();
                    ApprovalDetail::insert($array_post);
                }else{
                    ApprovalDetail::insert($array_post);
                }
            });
            return ResponseFormatter::success(
                $post,
                'Master Approver successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Approver failed to update',
        //         500
        //     );
        // }
    }

    function updateInspectionGoodsApproval(Request $request, UpdateInspectionGoodsApprovalRequest $updateInspectionGoodsApprovalRequest) {
          // try {
            $updateInspectionGoodsApprovalRequest->validated();

                    $header     = ApprovalHistory::where('transaction_code',$request->transaction_code)->orderBy('id','desc')->first();
                    $approval   =0;
                    $approvalStep   =0;
                    $approvalHeader   ='';
                    $statusHeader = '';
                    $head               = '';
                    $detail               = '';
                    $validation               = '';
                    $postStatusHeader =[];

                    if($request->inspection_type == 3 && $request->type == 1){
                        $detail             = InspectionGoodsDetail::where('detail_inspection_code', $request->transaction_code)->first();
                        $approvalHeader     = ApprovalHeader::where([
                            'inspection_type'   => 3,
                            'type'              => 1,
                            'shift'             =>$detail->shift
                        ])->first();
                        $head               = InspectionGood::where('inspection_code', $approvalHeader->inspection_code)->first();
                        $currentApproval    = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $approvalStep       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $lastApproval       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('step', $approvalStep->step + 1 )->first();
                        $approval           = $lastApproval === null ? '' :  $lastApproval->user_id;
                        $approvalStep       = $approvalStep === null ? 0 : $approvalStep->step + 1;
                        $statusHeader       = $lastApproval == null ? $approvalHeader->status+ 1 : $approvalHeader->status;
                        $validation         = InspectionGoodsDetail::where('inspection_code', $detail->inspection_code)->where('status', 2)->count();
                        if($approvalStep == 0){
                            dd('test');
                        }
                        $post = [
                            'status'        => $request->approval_insepection_goods_shift_id,
                            'remark'        => $request->ig_remark
                        ];
                        $postHeader =[
                            'approval_id'           => $approval ? (int) $approval : 0,
                            'status'                => $approval ? $detail->status : $detail->status+1
                        ];
                        $postapproval = [
                            'user_id'          => $approval ? (int) $approval : null,
                            'transaction_code' => $request->transaction_code,
                            'inspection_type'  => (int) $request->inspection_type,
                            'type'             => (int) $request->type,
                            'status'           => 0,
                            'step'             => (int) $approvalStep,
                            'remark'           => 'waiting for approval',
                        ];
                        $postStatusHeader =[
                            'status'    =>$statusHeader,
                        ];
                    }
                    // dd($lastApproval);
                    if($request->inspection_type == 2 && $request->type == 1){
                        $detail             = InspectionMaterial::where('inspection_code', $request->transaction_code)->first();

                        $approvalHeader     = ApprovalHeader::where([
                            'inspection_type'   => 2,
                            'type'              => 1,
                            'location'          =>$detail->location
                        ])->first();

                        $currentApproval    = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $approvalStep       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $lastApproval       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('step', $approvalStep->step + 1 )->first();
                        $approval           = $lastApproval === null ? '' :  $lastApproval->user_id;
                        $approvalStep       = $approvalStep === null ? 0 : $approvalStep->step + 1;
                        $statusHeader       = $lastApproval == null ? $detail->status+ 1 : $detail->status;
                        $validation         = InspectionMaterial::where('inspection_code', $detail->inspection_code)->where('status', 2)->count();
                        $post = [
                            'status'        => $request->approval_resin,
                            'remark'        => $request->imr_remark
                        ];
                        $postapproval = [
                            'user_id'          => $approval ? (int) $approval : null,
                            'transaction_code' => $request->transaction_code,
                            'inspection_type'  => (int) $request->inspection_type,
                            'type'             => (int) $request->type,
                            'status'           => 0,
                            'step'             => (int) $approvalStep,
                            'remark'           => 'waiting for approval',
                        ];

                        $postHeader =[
                            'approval_id'           => $approval ? (int) $approval : 0,
                            'status'                => $approval ? $detail->status : $detail->status+1
                        ];
                        $postStatusHeader =[
                            'status'    =>$statusHeader,
                        ];
                        // dd($statusHeader);
                    }
                    if($request->inspection_type == 2 && $request->type == 2){
                        $detail             = InspectionMaterial::where('inspection_code', $request->label_pellet_inspection_code)->first();

                        $approvalHeader     = ApprovalHeader::where([
                            'inspection_type'   => 2,
                            'type'              => 1,
                            'location'          =>$detail->location
                        ])->first();

                        $countApproval    = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->count();
                        $currentApproval    = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $approvalStep       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('user_id',auth()->user()->id)->first();
                        $lastApproval       = ApprovalDetail::where('approval_code',$approvalHeader->approval_code)->where('step', $approvalStep->step + 1 )->first();
                        $approval           = $lastApproval === null ? '' :  $lastApproval->user_id;
                        $approvalStep       = $approvalStep === null ? 0 : $approvalStep->step + 1;
                        $statusHeader       = $lastApproval == null ? $detail->status+ 1 : $detail->status;
                        $validation         = InspectionMaterial::where('inspection_code', $detail->inspection_code)->where('status', 2)->count();

                        $post = [
                            'status'        => $request->approval_resin ?? $request->approval_pellet ,
                            'remark'        => $request->imr_remark ?? $request->imr_pellet_remark,
                        ];
                        $postapproval = [
                            'user_id'          => $approval ? (int) $approval : null,
                            'transaction_code' => $request->transaction_code == null ? $detail->inspection_code : $request->transaction_code,
                            'inspection_type'  => (int) $request->inspection_type,
                            'type'             => (int) $request->type,
                            'status'           => 0,
                            'step'             => (int) $approvalStep,
                            'remark'           => 'waiting for approval',
                        ];

                        $postHeader =[
                            'approval_id'           => $approval ? (int) $approval : 0,
                            'status'                => $approval ? $detail->status : $detail->status+1
                        ];
                        $postStatusHeader =[
                            'status'    =>$statusHeader,
                        ];
                        // dd($statusHeader);
                    }

                    DB::transaction(function() use($request,$post,$postapproval,$approvalStep, $postHeader,$currentApproval,$approval,$detail,$approvalHeader, $statusHeader,$postStatusHeader,$validation, $countApproval) {
                        if(($request->inspection_type == 2 && $request->type == 1) || ($request->inspection_type == 2 && $request->type == 2) ){

                            $user = auth()->user()->id;
                            $update = ApprovalHistory::where([
                                'transaction_code'  => $request->transaction_code ?? $request->label_pellet_inspection_code,
                                'step'              => $currentApproval->step,
                                'user_id'           => $user
                            ])->update($post);
                            if($post['status'] == 1 || $post['status'] == '1'){
                                if($approval !==''){
                                    ApprovalHistory::create($postapproval);
                                }
                                if($approval ==''){
                                    InspectionMaterial::where('inspection_code', $request->transaction_code ?? $request->label_pellet_inspection_code)->update($postStatusHeader);
                                }
                            }

                        }

                        else{
                            InspectionGoodsDetail::where('detail_inspection_code', $request->transaction_code)->update($postHeader);
                            $user = auth()->user()->id;
                            ApprovalHistory::where([
                                'transaction_code'  => $request->transaction_code,
                                'step'              => $currentApproval->step,
                                'user_id'           => $user
                            ])->update($post);
                            if($approval != null){
                                if($request->approval_insepection_goods_shift_id == "1"){
                                    ApprovalHistory::create($postapproval);
                               }
                            }
                            if($approvalStep == 0 || $approval ==''){
                                InspectionGood::where('inspection_code', $approvalHeader->inspection_code)->update($postStatusHeader);
                            }
                            if($validation == 3){
                                InspectionGood::where('inspection_code', $detail->inspection_code)->update(['status' => 2]);
                            }
                        }
                });
                return ResponseFormatter::success(
                    $post,
                    'Approval successfully updated'
                );

        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Master Approver failed to update',
        //         500
        //     );
        // }
    }

}
