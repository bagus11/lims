<?php

namespace App\Http\Controllers\Master;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\MasterProduct\UpdateProductRequest;
use App\Imports\Master\UploadMasterProduct;
use App\Models\Master\LogMasterProduct;
use App\Models\Master\MasterProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class MasterProductController extends Controller
{
    function index() {
        return view('master.master_product.master_product-index');
    }
    function getMasterProduct() {
        $data = MasterProduct::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function uploadMasterProduct(Request $request) {
        // try{
            $request->validate([
                'upload_file' => 'required|mimes:xlsx',
            ]);
        
            $upload = Excel::import(new UploadMasterProduct, $request->file('upload_file'));
            return ResponseFormatter::success(
                $upload,
                'Master Product Uploaded Successfully'
            );            
    //  } catch (\Throwable $th) {
    //      return ResponseFormatter::error(
    //          $th,
    //          'Master Product failed to upload',
    //          500
    //      );
    //  }
     
    }
    function getDetailProductLog(Request $request) {
        $detail = MasterProduct::find($request->id);
        // dd($request);
        $log = LogMasterProduct::with('userRelation')->where([
            'seri'  =>$detail->seri,
            'join'  =>$detail->join,
            'marking'  =>$detail->marking,
        ])->get();
        return response()->json([
            'log'=>$log,
            'detail'=>$detail,
        ]);
    }
    function updateProduct(Request $request, UpdateProductRequest $updateProductRequest) {
        // try {
            $updateProductRequest->validated();
            $detail = MasterProduct::find($request->id);
            // dd($detail);
            $post = [
                'berat_pcs' => $request->detail_berat_pcs ==null ?'' : $request->detail_berat_pcs,
                'berat' => $request->detail_berat ==null ?'' : $request->detail_berat,
                'formula' => $request->detail_formula ==null ?'' : $request->detail_formula,
                'min_od' => $request->detail_min_od ==null ?'' : $request->detail_min_od,
                'toleransi_od' => $request->detail_toleransi_od ==null ?'' : $request->detail_toleransi_od,
                'max_od' => $request->detail_max_od ==null ?'' : $request->detail_max_od,
                'avg_od' => $request->detail_average_od ==null ?'' : $request->detail_average_od,
                'min_wt' => $request->detail_min_wt ==null ?'' : $request->detail_min_wt,
                'toleransi_wt' => $request->detail_toleransi_wt ==null ?'' : $request->detail_toleransi_wt,
                'max_wt' => $request->detail_max_wt ==null ?'' : $request->detail_max_wt,
                'avg_wt' => $request->detail_average_wt ==null ?'' : $request->detail_average_wt,
                'updated_at'=>date('Y-m-d H:i:s')
            ];
            $post_log=[
                'seri' => $detail->seri,
                'join' => $detail->join,
                'ukuran' => $detail->ukuran,
                'marking' => $detail->marking,
                'berat_pcs' => $request->detail_berat_pcs ==null ?'' : $request->detail_berat_pcs,
                'berat' => $request->detail_berat ==null ?'' : $request->detail_berat,
                'kode_ts' => $detail->kode_ts,
                'kode_be' => $detail->kode_be,
                'marking_2' => $detail->marking_2,
                'formula' => $request->detail_formula ==null ?'' : $request->detail_formula,
                'min_od' => $request->detail_min_od ==null ?'' : $request->detail_min_od,
                'toleransi_od' => $request->detail_toleransi_od ==null ?'' : $request->detail_toleransi_od,
                'marking_od' => $detail->marking_od,
                'max_od' => $request->detail_max_od ==null ?'' : $request->detail_max_od,
                'avg_od' => $request->detail_average_od ==null ?'' : $request->detail_average_od,
                'min_wt' => $request->detail_min_wt ==null ?'' : $request->detail_min_wt,
                'toleransi_wt' => $request->detail_toleransi_wt ==null ?'' : $request->detail_toleransi_wt,
                'marking_wt' => $detail->marking_wt,
                'max_wt' => $request->detail_max_wt ==null ?'' : $request->detail_max_wt,
                'avg_wt' => $request->detail_average_wt ==null ?'' : $request->detail_average_wt,
                'user_id' => auth()->user()->id,
                'created_at'=>date('Y-m-d H:i:s')
            ];
            DB::transaction(function() use($post,$post_log,$request) {
              MasterProduct::find($request->id)->update($post);
              LogMasterProduct::create($post_log);
                
            });
            return ResponseFormatter::success(
               $post,
                'Product successfully updated'
            );            
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Product failed to update',
        //         500
        //     );
        // }
    }
}
