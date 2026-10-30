<?php

namespace App\Http\Controllers\Setting;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Approval\StoreApprovalHeaderRequest;
use App\Http\Requests\Setting\Approval\UpdateLaboratoryApprovalRequest;
use App\Http\Requests\Setting\Approval\UpdateResalesGoodsApprovalRequest;
use App\Http\Requests\Setting\Approval\UpdateApprovalRequest;
use App\Http\Requests\Setting\Approval\UpdateInspectionGoodsApprovalRequest;
use App\Models\Master\MasterLocation;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\InspectionGoodsDetail;
use App\Models\Transaction\Inspection\InspectionGoodsSubDetail;
use App\Models\Transaction\Inspection\Laboratory\LaboratoryHeader;
use App\Models\Transaction\Inspection\ResalesGood;
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
        if (!$header) {
            return ResponseFormatter::error(null, 'Approval setting not found for this location and shift', 404);
        }
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
            $header = ApprovalHeader::where('approval_code',$request->approval_id)->first();
            if (!$header) {
                return ResponseFormatter::error(null, 'Approval header not found', 404);
            }
            $array_post=[];
            foreach($request->user_array as $row){
                $array_post[] = [
                    'user_id'           => $row['user_id'],
                    'step'              => $row['step'],
                    'approval_code'     =>$request->approval_id,
                    'inspection_type'   =>$header->inspection_type,
                    'created_at'        =>date('Y-m-d H:i:s')
                ];
            }
            DB::transaction(function() use($array_post,$request) {
                ApprovalDetail::where('approval_code',$request->approval_id)->delete();
                ApprovalDetail::insert($array_post);
            });
            return ResponseFormatter::success(
                $array_post,
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

    /**
     * Approval Inspection Goods (type 3) & Inspection Material (type 2).
     * Endpoint & field request lama dipertahankan agar frontend tidak berubah:
     * - type 3        : approval_insepection_goods_shift_id + ig_remark + transaction_code (detail_inspection_code)
     * - type 2 resin  : approval_resin + imr_remark + transaction_code
     * - type 2 pellet : approval_pellet + imr_pellet_remark + label_pellet_inspection_code
     * Decision: 1 = Approve, 2 = Reject (reject membuka kunci untuk diperbaiki).
     */
    function updateInspectionGoodsApproval(Request $request, UpdateInspectionGoodsApprovalRequest $updateInspectionGoodsApprovalRequest) {
        $updateInspectionGoodsApprovalRequest->validated();

        if ((int) $request->inspection_type === 2) {
            $code = $request->transaction_code ?? $request->label_pellet_inspection_code;
            $decision = (int) ($request->approval_resin ?? $request->approval_pellet);
            $remark = trim((string) ($request->imr_remark ?? $request->imr_pellet_remark));
            $result = $this->approveInspectionMaterial($code, $decision, $remark);
        } else {
            $result = $this->approveInspectionGoodsShift(
                $request->transaction_code,
                (int) $request->approval_insepection_goods_shift_id,
                trim((string) $request->ig_remark)
            );
        }

        return ResponseFormatter::success($result, 'Approval successfully updated');
    }

    /**
     * History approval aktif milik user login — satu-satunya tiket sah untuk
     * memberi keputusan; tanpa ini siapa pun di master approver bisa approve
     * out-of-order / berulang.
     */
    private function activeApprovalHistory(string $transactionCode, int $inspectionType)
    {
        return ApprovalHistory::where([
            'transaction_code' => $transactionCode,
            'inspection_type' => $inspectionType,
            'user_id' => auth()->user()->id,
            'status' => 0,
        ])->orderBy('id', 'desc')->lockForUpdate()->first();
    }

    private function approveInspectionMaterial($code, int $decision, string $remark): array
    {
        return DB::transaction(function () use ($code, $decision, $remark) {
            $header = InspectionMaterial::where('inspection_code', $code)->lockForUpdate()->first();
            if (!$header) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Inspection Material transaction not found.'],
                ]);
            }
            if (!(int) $header->lock || (int) $header->status !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Transaction is not waiting for approval.'],
                ]);
            }

            $history = $this->activeApprovalHistory($header->inspection_code, 2);
            if (!$history) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['You are not the active approver for this transaction.'],
                ]);
            }

            $history->update(['status' => $decision, 'remark' => $remark]);

            if ($decision === 2) {
                // Reject: status 4 + kunci dibuka supaya hasil uji bisa
                // diperbaiki dan transaksi di-lock ulang
                InspectionMaterial::where('inspection_code', $header->inspection_code)->update([
                    'status' => 4,
                    'lock' => 0,
                    'approval_id' => 0,
                ]);

                return ['transaction_code' => $header->inspection_code, 'status' => 4];
            }

            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 2,
                'shift' => 1,
                'location' => $header->location,
            ])->first();
            if (!$approvalHeader) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Approval setting was not found for this location.'],
                ]);
            }

            $next = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
                ->where('step', $history->step + 1)
                ->first();

            if ($next) {
                ApprovalHistory::create([
                    'transaction_code' => $header->inspection_code,
                    'type' => 1,
                    'inspection_type' => 2,
                    'step' => $history->step + 1,
                    'user_id' => $next->user_id,
                    'status' => 0,
                    'remark' => 'waiting for approval',
                ]);
                InspectionMaterial::where('inspection_code', $header->inspection_code)->update([
                    'approval_id' => $next->user_id,
                ]);

                return ['transaction_code' => $header->inspection_code, 'status' => 2];
            }

            InspectionMaterial::where('inspection_code', $header->inspection_code)->update([
                'status' => 3,
                'approval_id' => 0,
            ]);

            return ['transaction_code' => $header->inspection_code, 'status' => 3];
        });
    }

    private function approveInspectionGoodsShift($detailCode, int $decision, string $remark): array
    {
        return DB::transaction(function () use ($detailCode, $decision, $remark) {
            $detail = InspectionGoodsDetail::where('detail_inspection_code', $detailCode)->lockForUpdate()->first();
            if (!$detail) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Inspection shift transaction not found.'],
                ]);
            }
            if (!(int) $detail->lock || (int) $detail->status !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Transaction is not waiting for approval.'],
                ]);
            }

            $header = InspectionGood::where('inspection_code', $detail->inspection_code)->lockForUpdate()->first();
            if (!$header) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Inspection header not found.'],
                ]);
            }

            $history = $this->activeApprovalHistory($detail->detail_inspection_code, 3);
            if (!$history) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['You are not the active approver for this transaction.'],
                ]);
            }

            $history->update(['status' => $decision, 'remark' => $remark]);

            if ($decision === 2) {
                // Reject: shift kembali Incomplete + kunci dibuka
                InspectionGoodsDetail::where('detail_inspection_code', $detail->detail_inspection_code)->update([
                    'status' => 0,
                    'lock' => 0,
                    'approval_id' => 0,
                ]);
                InspectionGoodsSubDetail::where('inspection_detail', $detail->detail_inspection_code)
                    ->update(['status' => 0]);

                $otherLocked = InspectionGoodsDetail::where('inspection_code', $detail->inspection_code)
                    ->where('detail_inspection_code', '!=', $detail->detail_inspection_code)
                    ->where('lock', 1)
                    ->exists();
                InspectionGood::where('inspection_code', $detail->inspection_code)
                    ->update(['status' => $otherLocked ? 2 : 1]);

                return ['transaction_code' => $detail->detail_inspection_code, 'status' => 0];
            }

            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 3,
                'shift' => $detail->shift,
                'location' => $header->location_id,
            ])->first();
            if (!$approvalHeader) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Approval setting was not found for this location and shift.'],
                ]);
            }

            $next = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
                ->where('step', $history->step + 1)
                ->first();

            if ($next) {
                ApprovalHistory::create([
                    'transaction_code' => $detail->detail_inspection_code,
                    'type' => 1,
                    'inspection_type' => 3,
                    'step' => $history->step + 1,
                    'user_id' => $next->user_id,
                    'status' => 0,
                    'remark' => 'waiting for approval',
                ]);
                InspectionGoodsDetail::where('detail_inspection_code', $detail->detail_inspection_code)->update([
                    'approval_id' => $next->user_id,
                ]);

                return ['transaction_code' => $detail->detail_inspection_code, 'status' => 1];
            }

            // Step terakhir: shift Complete; bila ketiga shift sudah Complete,
            // header DONE (dulu update ini salah kolom -> silent no-op selamanya)
            InspectionGoodsDetail::where('detail_inspection_code', $detail->detail_inspection_code)->update([
                'status' => 2,
                'approval_id' => 0,
            ]);
            $completed = InspectionGoodsDetail::where('inspection_code', $detail->inspection_code)
                ->where('status', 2)
                ->count();
            if ($completed >= 3) {
                InspectionGood::where('inspection_code', $detail->inspection_code)->update(['status' => 3]);
            }

            return ['transaction_code' => $detail->detail_inspection_code, 'status' => 2];
        });
    }

    function updateLaboratoryApproval(Request $request, UpdateLaboratoryApprovalRequest $updateLaboratoryApprovalRequest)
    {
        $updateLaboratoryApprovalRequest->validated();

        $result = DB::transaction(function () use ($request) {
            $laboratory = LaboratoryHeader::where('laboratory_code', $request->laboratory_code)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$laboratory->lock || (int) $laboratory->status !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['Laboratory transaction is not waiting for approval.'],
                ]);
            }

            $history = ApprovalHistory::where([
                'transaction_code' => $laboratory->laboratory_code,
                'inspection_type' => 1,
                'user_id' => auth()->user()->id,
                'status' => 0,
            ])->orderBy('id', 'desc')->lockForUpdate()->first();

            if (!$history || (int) $laboratory->approval_id !== (int) auth()->user()->id) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['You are not the active approver for this transaction.'],
                ]);
            }

            $history->update([
                'status' => (int) $request->decision,
                'remark' => trim($request->remark),
            ]);

            if ((int) $request->decision === 4) {
                $laboratory->update([
                    'status' => 4,
                    'lock' => 0,
                    'current_approval' => 0,
                    'approval_id' => null,
                    'completed_at' => null,
                ]);

                return [
                    'laboratory_code' => $laboratory->laboratory_code,
                    'status' => 4,
                ];
            }

            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 1,
                'type' => 1,
                'location' => $laboratory->location_id,
                'shift' => $laboratory->shift ?: 1,
                'status' => 1,
            ])->first();

            if (!$approvalHeader) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['Active Laboratory approval setting was not found.'],
                ]);
            }

            $nextApproval = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
                ->where('step', $history->step + 1)
                ->first();

            if ($nextApproval) {
                ApprovalHistory::create([
                    'transaction_code' => $laboratory->laboratory_code,
                    'type' => 1,
                    'inspection_type' => 1,
                    'step' => $history->step + 1,
                    'user_id' => $nextApproval->user_id,
                    'status' => 0,
                    'remark' => 'Waiting for Laboratory approval',
                ]);

                $laboratory->update([
                    'current_approval' => $history->step + 1,
                    'approval_id' => $nextApproval->user_id,
                ]);
            } else {
                $laboratory->update([
                    'status' => 3,
                    'current_approval' => $history->step,
                    'approval_id' => null,
                    'completed_at' => now(),
                ]);
            }

            return [
                'laboratory_code' => $laboratory->laboratory_code,
                'status' => $nextApproval ? 2 : 3,
            ];
        });

        return ResponseFormatter::success($result, 'Laboratory approval successfully updated');
    }

    /**
     * Handler approval Resales Goods (inspection_type = 4).
     * Status header: 0 Draft, 1 In Progress, 2 Approval Progress, 3 Done.
     * decision 1 = approve (lanjut step berikutnya / Done), 4 = revision
     * (kembali ke In Progress supaya bisa diedit dan di-lock ulang).
     */
    function updateResalesGoodsApproval(Request $request, UpdateResalesGoodsApprovalRequest $updateResalesGoodsApprovalRequest)
    {
        $updateResalesGoodsApprovalRequest->validated();

        $result = DB::transaction(function () use ($request) {
            $header = ResalesGood::where('transaction_code', $request->transaction_code)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $header->status !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Resales Goods transaction is not waiting for approval.'],
                ]);
            }

            $history = ApprovalHistory::where([
                'transaction_code' => $header->transaction_code,
                'inspection_type' => 4,
                'user_id' => auth()->user()->id,
                'status' => 0,
            ])->orderBy('id', 'desc')->lockForUpdate()->first();

            if (!$history) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['You are not the active approver for this transaction.'],
                ]);
            }

            $history->update([
                'status' => (int) $request->decision,
                'remark' => trim($request->remark),
            ]);

            if ((int) $request->decision === 4) {
                ResalesGood::where('transaction_code', $header->transaction_code)->update([
                    'status' => 1,
                    'current_approval' => 0,
                ]);

                return [
                    'transaction_code' => $header->transaction_code,
                    'status' => 1,
                ];
            }

            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 4,
                'location' => $header->location_id,
            ])->first();

            if (!$approvalHeader) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'transaction_code' => ['Resales Goods approval setting was not found for this location.'],
                ]);
            }

            $nextApproval = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
                ->where('step', $history->step + 1)
                ->first();

            if ($nextApproval) {
                ApprovalHistory::create([
                    'transaction_code' => $header->transaction_code,
                    'type' => 1,
                    'inspection_type' => 4,
                    'step' => $history->step + 1,
                    'user_id' => $nextApproval->user_id,
                    'status' => 0,
                    'remark' => 'Approval for Resales Goods Inspection',
                ]);

                ResalesGood::where('transaction_code', $header->transaction_code)->update([
                    'current_approval' => $history->step + 1,
                ]);
            } else {
                ResalesGood::where('transaction_code', $header->transaction_code)->update([
                    'status' => 3,
                    'current_approval' => $history->step,
                ]);
            }

            return [
                'transaction_code' => $header->transaction_code,
                'status' => $nextApproval ? 2 : 3,
            ];
        });

        return ResponseFormatter::success($result, 'Resales Goods approval successfully updated');
    }

    function updateLaboratoryApproval(Request $request, UpdateLaboratoryApprovalRequest $updateLaboratoryApprovalRequest)
    {
        $updateLaboratoryApprovalRequest->validated();

        $result = DB::transaction(function () use ($request) {
            $laboratory = LaboratoryHeader::where('laboratory_code', $request->laboratory_code)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$laboratory->lock || (int) $laboratory->status !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['Laboratory transaction is not waiting for approval.'],
                ]);
            }

            $history = ApprovalHistory::where([
                'transaction_code' => $laboratory->laboratory_code,
                'inspection_type' => 1,
                'user_id' => auth()->user()->id,
                'status' => 0,
            ])->orderBy('id', 'desc')->lockForUpdate()->first();

            if (!$history || (int) $laboratory->approval_id !== (int) auth()->user()->id) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['You are not the active approver for this transaction.'],
                ]);
            }

            $history->update([
                'status' => (int) $request->decision,
                'remark' => trim($request->remark),
            ]);

            if ((int) $request->decision === 4) {
                $laboratory->update([
                    'status' => 4,
                    'lock' => 0,
                    'current_approval' => 0,
                    'approval_id' => null,
                    'completed_at' => null,
                ]);

                return [
                    'laboratory_code' => $laboratory->laboratory_code,
                    'status' => 4,
                ];
            }

            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 1,
                'type' => 1,
                'location' => $laboratory->location_id,
                'shift' => $laboratory->shift ?: 1,
                'status' => 1,
            ])->first();

            if (!$approvalHeader) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'laboratory_code' => ['Active Laboratory approval setting was not found.'],
                ]);
            }

            $nextApproval = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
                ->where('step', $history->step + 1)
                ->first();

            if ($nextApproval) {
                ApprovalHistory::create([
                    'transaction_code' => $laboratory->laboratory_code,
                    'type' => 1,
                    'inspection_type' => 1,
                    'step' => $history->step + 1,
                    'user_id' => $nextApproval->user_id,
                    'status' => 0,
                    'remark' => 'Waiting for Laboratory approval',
                ]);

                $laboratory->update([
                    'current_approval' => $history->step + 1,
                    'approval_id' => $nextApproval->user_id,
                ]);
            } else {
                $laboratory->update([
                    'status' => 3,
                    'current_approval' => $history->step,
                    'approval_id' => null,
                    'completed_at' => now(),
                ]);
            }

            return [
                'laboratory_code' => $laboratory->laboratory_code,
                'status' => $nextApproval ? 2 : 3,
            ];
        });

        return ResponseFormatter::success($result, 'Laboratory approval successfully updated');
    }

}
