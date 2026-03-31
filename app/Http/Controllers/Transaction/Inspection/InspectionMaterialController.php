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
    function getInspectionMaterial(Request $request) {
        $data = InspectionMaterial::with([
            'locationRelation',
            'inspectorRelation',
            'materialRelation',
            // 'supplierRelation',
            'userRelation',
        ])
        // ->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to])
        ->get();
        return response()->json([
            'data'=>$data
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
        $ticket_code    ='';
        $addInspectionMaterialRequest->validated();
        $increment_code= InspectionMaterial::orderBy('id','desc')->first();
        $date_month =strtotime(date('Y-m-d'));
        $month =idate('m', $date_month);
        $year = idate('y', $date_month);
        $month_convert =  NumConvert::roman($month);
        if($increment_code ==null){
            $ticket_code = '1/INSM/'.$month_convert.'/'.$year;
        }else{
            $month_before = explode('/',$increment_code->inspection_code,-1);
            if($month_convert != $month_before[2]){
                $ticket_code = '1/INSM/'.$month_convert.'/'.$year;
            }else{
                $ticket_code = $month_before[0] + 1 .'/INSM/'.$month_convert.'/'.$year;
            }
        }
        $post =[
            'inspection_code'   =>$ticket_code,
            'location'          =>$request->location,
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
        DB::transaction(function() use($post,$request,$post_resin,$ticket_code,$step,$post_pellet) {
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
        $header =Pellet::where('inspection_code', $request->inspection_code)->first();
        return response()->json([
            'header'=>$header,
            'detail'=>$detail,
        ]);
    }
    function updateResinDetail(Request $request) {
            // try{
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

                $header = InspectionMaterial::where('inspection_code',$request->inspection_code)->first();
                $approval = ApprovalHeader::where([
                    'inspection_type'   =>  2,
                    'shift'             => 1,
                    'location'          =>  $header->location
                ])->first();
                $approval_id = ApprovalDetail::where([
                    'approval_code'     => $approval->approval_code,
                    'step'              => 1
                ])->first();
                $post = [
                    'lock'              => 1,
                    'status'            => $header->status + 1,
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

        if($detail->type == 2){
            $header =Pellet::where('inspection_code', $inspection_code)->first();
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
        // dd($values);
           if (!empty($values)) {

                // 🔹 Hitung min & max
                $minVal = min($values);
                $maxVal = max($values);

                // 🔹 Handle kalau semua nilai sama (biar grafik gak flat)
                if ($minVal == $maxVal) {
                    if ($minVal == 0) {
                        $maxVal = 1; // biar ada range
                    } else {
                        $minVal = $minVal - 1;
                        $maxVal = $maxVal + 1;
                    }
                }

                // 🔹 Cek apakah semua 0 (optional fallback)
                $allZero = count(array_filter($values)) === 0;

                // 🔹 Config chart
                $chartConfig = [
                    "type" => "line",
                    "data" => [
                        "labels" => $steps,
                        "datasets" => [[
                            "label" => "Bulk Density",
                            "data" => $values,
                            "fill" => false,
                            "borderColor" => "#2FA4D7",
                            "borderWidth" => 2,
                            "pointRadius" => 3,
                            "pointBackgroundColor" => "#2FA4D7",
                        ]]
                    ],
                    "options" => [
                        "legend" => ["display" => true],

                        "plugins" => [
                            "title" => [
                                "display" => $allZero,
                                "text" => "Semua nilai = 0"
                            ]
                        ],

                        "scales" => [
                            "y" => [
                                "min" => $minVal,
                                "max" => $maxVal
                            ]
                        ]
                    ]
                ];

                // 🔹 Generate URL QuickChart
                $chartUrl = "https://quickchart.io/chart?width=500&height=300&c=" . urlencode(json_encode($chartConfig));

            } else {
                $chartUrl = null;
            }
        }

        $data = [
                'detail'   => $detail,
                'header'   => $header,
                'data'     => $data,
                'min'      => $min,
                'max'      => $max,
                'avg'      => $avg,
                'chartUrl' => $chartUrl,
            ];
        if($detail->type == 1){
            $cetak              = view('report.inspection.report-resin_material', $data);
        }else{
            $cetak              = view('report.inspection.report-pellet_material', $data);
        }
        $imageLogo          = '<img src="'.public_path('icon.png').'" width="70px" style="float: right;"/>';
        $header             = '';
        $header             .= '<table width="100%" >
                                    <tr>
                                        <td style="border:none;padding-left:10px;">
                                            <span style="font-size: 16px; font-weight: bold;"> PT PRALON</span>
                                            <br>
                                            <span style="font-size:9px;">Synergy Building #08-08 Tangerang 15143 - Indonesia +62 21 304 38808</span>
                                        </td>
                                        <td style="border:none;width:33%"></td>
                                            <td style="border:none;width: 50px; text-align:right;">'.$imageLogo.'
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
}
