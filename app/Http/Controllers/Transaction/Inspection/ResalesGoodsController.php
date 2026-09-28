<?php

namespace App\Http\Controllers\Transaction\Inspection;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Inspection\ResalesGoods\StoreResalesGoodDetailRequest;
use App\Http\Requests\Transaction\Inspection\ResalesGoods\StoreResalesGoodHeaderRequest;
use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProductResalesGood;
use App\Models\Master\Supplier;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\ResalesGood;
use App\Models\Transaction\Inspection\ResalesGoodDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use NumConvert;

class ResalesGoodsController extends Controller
{
    function index() {
        return view('transaction.inspection.resales-goods.resales-goods-index');
    }

    function getOptionLists() {
        $locations  = MasterLocation::all();
        $products   = MasterProductResalesGood::all();
        $suppliers  = Supplier::all();
        $inspectors = User::all();

        return response()->json([
            'data' => [
                'locations'  => $locations,
                'products'   => $products,
                'suppliers'  => $suppliers,
                'inspectors' => $inspectors,
            ]
        ]);
    }

    function getResalesGood(Request $request) {
        $query = ResalesGood::with([
            'locationRelation',
            'productRelation',
            'inspectorRelation',
            'supplierRelation',
            'produsenRelation',
            'createdByRelation',
        ]);

        if ($request->has('from') && $request->has('to') && $request->from != '' && $request->to != '') {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to]);
        }

        if (!auth()->user()->hasPermissionTo('get-only_gm-inspection-goods')) {
            $query->where('location_id', auth()->user()->kode_kantor);
        }

        $data = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    function getResalesGoodDetails(Request $request) {
        $data = ResalesGoodDetail::with([
            'productRelation',
            'createdByRelation',
        ])->where('transaction_code', $request->transaction_code)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    function addResalesGoodsHeader(Request $request, StoreResalesGoodHeaderRequest $storeHeaderRequest) {
        // try {
            $storeHeaderRequest->validated();

            $increment_code = ResalesGood::orderBy('id', 'desc')->first();
            $date_month = strtotime(date('Y-m-d'));
            $month = idate('m', $date_month);
            $year = idate('y', $date_month);
            $month_convert = NumConvert::roman($month);

            if ($increment_code == null) {
                $ticket_code = '1/INSRG/' . $month_convert . '/' . $year;
            } else {
                $last_code = $increment_code->transaction_code;
                $month_before = explode('/', $last_code, -1);
                if ($month_convert != $month_before[2]) {
                    $ticket_code = '1/INSRG/' . $month_convert . '/' . $year;
                } else {
                    $ticket_code = ($month_before[0] + 1) . '/INSRG/' . $month_convert . '/' . $year;
                }
            }

            $post = [
                'transaction_code'  => $ticket_code,
                'location_id'       => $request->location_id,
                'product_id'        => $request->product_id,
                'inspector_id'      => $request->inspector_id,
                'supplier_id'       => $request->supplier_id,
                'produsen_id'       => $request->produsen_id,
                'brand'             => $request->brand,
                'packaging'         => $request->packaging,
                'certificate'       => $request->certificate,
                'quantity'          => $request->quantity,
                'standard'          => $request->standard,
                'type'              => $request->type,
                'current_approval'  => 0,
                'status'            => 0,
                'created_by'        => auth()->user()->id,
            ];

            DB::transaction(function () use ($post) {
                ResalesGood::create($post);
            });

            return ResponseFormatter::success(
                $post,
                'Resales Goods transaction successfully created'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Resales Goods transaction failed to create',
        //         500
        //     );
        // }
    }

    function addResalesGoodsDetail(Request $request, StoreResalesGoodDetailRequest $storeDetailRequest) {
        // try {
            $storeDetailRequest->validated();

            $header = ResalesGood::where('transaction_code', $request->transaction_code)->first();
            if (!$header) {
                return ResponseFormatter::error(
                    null,
                    'Transaction header not found',
                    404
                );
            }

            $specs = $request->spesification;
            $clean_spec = [];
            foreach ($specs as $s) {
                $sym = trim($s['symbol'] ?? '');
                $val = trim($s['value'] ?? '');
                if ($sym != '' || $val != '') {
                    $clean_spec[] = ['symbol' => $sym, 'value' => $val];
                }
            }

            $post = [
                'transaction_code'  => $request->transaction_code,
                'product_id'        => $header->product_id,
                'spesification'     => $clean_spec,
                'created_by'        => auth()->user()->id,
            ];

            DB::transaction(function () use ($post, $header) {
                ResalesGoodDetail::create($post);
                if ($header->status == 0) {
                    ResalesGood::where('transaction_code', $header->transaction_code)
                        ->update(['status' => 1]);
                }
            });

            return ResponseFormatter::success(
                $post,
                'Resales Goods detail successfully added'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Resales Goods detail failed to add',
        //         500
        //     );
        // }
    }

    function lockResalesGoods(Request $request) {
        // try {
            $header = ResalesGood::where('transaction_code', $request->transaction_code)->first();
            if (!$header) {
                return ResponseFormatter::error(
                    null,
                    'Transaction not found',
                    404
                );
            }
            if ($header->status != 1) {
                return ResponseFormatter::error(
                    null,
                    'Only In Progress transaction can be locked',
                    400
                );
            }

            $approval = ApprovalHeader::where([
                'inspection_type'   => 4,
                'location'          => $header->location_id,
            ])->first();

            if (!$approval) {
                return ResponseFormatter::error(
                    null,
                    'Approval setting not found for this location (inspection_type=4)',
                    404
                );
            }

            $approval_first = ApprovalDetail::where([
                'approval_code' => $approval->approval_code,
                'step'          => 1,
            ])->first();

            if (!$approval_first) {
                return ResponseFormatter::error(
                    null,
                    'Approval step not configured',
                    404
                );
            }

            $postUpdateHeader = [
                'status'            => 2,
                'current_approval'  => 1,
                'no_pf'             => $request->no_pf ? trim($request->no_pf) : null,
                'keterangan'        => $request->keterangan ? trim($request->keterangan) : null,
            ];

            $postApproval = [
                'transaction_code'  => $header->transaction_code,
                'status'            => 0,
                'user_id'           => $approval_first->user_id,
                'step'              => 1,
                'remark'            => 'Approval for Resales Goods Inspection',
                'inspection_type'   => 4,
                'type'              => 1,
            ];

            DB::transaction(function () use ($postUpdateHeader, $postApproval, $header) {
                ResalesGood::where('transaction_code', $header->transaction_code)
                    ->update($postUpdateHeader);
                ApprovalHistory::create($postApproval);
            });

            return ResponseFormatter::success(
                $postUpdateHeader,
                'Resales Goods locked, approval progress started'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Failed to lock Resales Goods',
        //         500
        //     );
        // }
    }

    function deleteResalesGoods(Request $request) {
        // try {
            $header = ResalesGood::where('transaction_code', $request->transaction_code)->first();
            if (!$header) {
                return ResponseFormatter::error(
                    null,
                    'Transaction not found',
                    404
                );
            }

            DB::transaction(function () use ($header) {
                ResalesGoodDetail::where('transaction_code', $header->transaction_code)->delete();
                ResalesGood::where('transaction_code', $header->transaction_code)->delete();
            });

            return ResponseFormatter::success(
                null,
                'Resales Goods transaction deleted'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Failed to delete Resales Goods',
        //         500
        //     );
        // }
    }

    function editResalesGoodsDetail(Request $request, StoreResalesGoodDetailRequest $storeDetailRequest) {
        // try {
            $storeDetailRequest->validated();

            $detail = ResalesGoodDetail::find($request->id);
            if (!$detail) {
                return ResponseFormatter::error(
                    null,
                    'Detail not found',
                    404
                );
            }

            $header = ResalesGood::where('transaction_code', $detail->transaction_code)->first();
            if (!$header) {
                return ResponseFormatter::error(
                    null,
                    'Transaction header not found',
                    404
                );
            }

            $specs = $request->spesification;
            $clean_spec = [];
            foreach ($specs as $s) {
                $sym = trim($s['symbol'] ?? '');
                $val = trim($s['value'] ?? '');
                if ($sym != '' || $val != '') {
                    $clean_spec[] = ['symbol' => $sym, 'value' => $val];
                }
            }

            $post = [
                'spesification' => $clean_spec,
            ];

            DB::transaction(function () use ($post, $detail) {
                $detail->update($post);
            });

            return ResponseFormatter::success(
                $post,
                'Resales Goods detail successfully updated'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Resales Goods detail failed to update',
        //         500
        //     );
        // }
    }

    function deleteResalesGoodsDetail(Request $request) {
        // try {
            $detail = ResalesGoodDetail::find($request->id);
            if (!$detail) {
                return ResponseFormatter::error(
                    null,
                    'Detail not found',
                    404
                );
            }

            $trxCode = $detail->transaction_code;

            DB::transaction(function () use ($detail, $trxCode) {
                $detail->delete();
                $remaining = ResalesGoodDetail::where('transaction_code', $trxCode)->count();
                if ($remaining == 0) {
                    ResalesGood::where('transaction_code', $trxCode)->update(['status' => 0]);
                }
            });

            return ResponseFormatter::success(
                null,
                'Resales Goods detail deleted'
            );
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Failed to delete Resales Goods detail',
        //         500
        //     );
        // }
    }

    function printResalesGoodsPdf(Request $request) {
        $trxCode = $request->get('transaction_code');
        if (!$trxCode) {
            abort(404, 'Transaction code required');
        }

        $header = ResalesGood::with([
            'locationRelation', 'productRelation', 'supplierRelation',
            'produsenRelation', 'inspectorRelation', 'createdByRelation'
        ])->where('transaction_code', $trxCode)->first();

        if (!$header) abort(404, 'Transaction not found');

        $details = ResalesGoodDetail::with(['productRelation', 'createdByRelation'])
            ->where('transaction_code', $trxCode)
            ->orderBy('id', 'ASC')
            ->get();

        $inspectionTypeResales = 4;
        $check_approval = \App\Models\Transaction\Inspection\ApprovalHistory::where('transaction_code', $trxCode)->first();
        $set_approval = \App\Models\Transaction\Inspection\ApprovalHistory::where('transaction_code', $trxCode)->orderBy('step', 'ASC')->get();
        $approval = '';
        if ($check_approval !== null) {
            $approvalHeader = \App\Models\Setting\Approval\ApprovalHeader::where([
                'inspection_type'   => $inspectionTypeResales,
                'type'              => 1,
                'location'          => $header->location_id
            ])->first();
            if ($approvalHeader) {
                $approval = \App\Models\Setting\Approval\ApprovalDetail::with([
                    'userRelation'
                ])->where('approval_code', $approvalHeader->approval_code)->orderBy('step', 'ASC')->get();
            }
        }

        $imageLogo = '';
        if (file_exists(public_path('icon.png'))) {
            $imageLogo = '<img src="'.public_path('icon.png').'" width="70px" style="float: right;"/>';
        }
        $headerHtml = '';
        $headerHtml .= '<table width="100%">
                                    <tr>
                                        <td style="padding-left:10px;">
                                            <span style="font-size: 16px; font-weight: bold;"> PT PRALON</span>
                                            <br>
                                            <span style="font-size:9px;">Synergy Building #08-08 Tangerang 15143 - Indonesia +62 21 304 38808</span>
                                        </td>
                                        <td style="width:33%">
                                            '.$imageLogo.'
                                        </td>
                                    </tr>
                               </table>';
        $footerHtml = '<table width="100%" style="font-size:8px; border-top:1px solid #ccc;color:#666;"><tr><td width="50%">Disclaimer: Dokumen ini dicetak otomatis oleh sistem LIMS PT Pralon.</td><td width="50%" align="right">Halaman {PAGENO}/{nbpg}</td></tr></table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->AddPage('L', '', '', '', '', 2, 2, 18, 18, 2, 0);
        $mpdf->WriteHTML(view('report.inspection.report_resales_goods', [
            'header'     => $header,
            'details'    => $details,
            'approval'   => $approval,
            'set_approval' => $set_approval,
        ]));
        ob_clean();
        $filename = 'LIMS_Resales_Goods_' . str_replace('/', '_', $trxCode) . '_' . date('Ymd_Hi') . '.pdf';
        $mpdf->Output($filename, 'I');
        exit;
    }

    function printResalesGoodsExcel(Request $request) {
        $trxCode = $request->get('transaction_code');
        if (!$trxCode) {
            abort(404, 'Transaction code required');
        }
        $filename = 'LIMS_Resales_Goods_' . str_replace('/', '_', $trxCode) . '_' . date('Ymd_Hi') . '.xlsx';
        return Excel::download(new \App\Exports\Transaction\Inspection\ResalesGoodsExport($trxCode), $filename);
    }
}
