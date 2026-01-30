<?php

namespace App\Http\Controllers\Master;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Masterial\UpdateMaterialRequest;
use App\Http\Requests\Master\Material\StoreMaterialRequest;
use App\Models\Master\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    function index() {
        return view('master.material.material-index');
    }
    function getMaterial() {
        $data = Material::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getActiveMaterial() {
        $data = Material::where('status',1)->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function addMaterial(Request $request , StoreMaterialRequest $storeMaterialRequest) {
           // try {
            $storeMaterialRequest->validated();
            $post = [
                'name'  =>$request->name,
                'spec'  =>$request->spec,
                'status'  =>1,
                'description'  =>$request->description,
            ];
            Material::create($post);
            return ResponseFormatter::success(
               $post,
                'Machine successfully added'
            );            
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Machine failed to update',
        //         500
        //     );
        // }
    }
    function updateMaterial(Request $request , UpdateMaterialRequest $storeMaterialRequest) {
           // try {
            $storeMaterialRequest->validated();
            $post = [
                'name'  =>$request->name_edit,
                'spec'  =>$request->spec_edit,
                'description'  =>$request->description_edit,
            ];
            Material::find($request->id)->update($post);
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
    function udpateStatusMaterial(Request $request)  {
          // try {
            $post = [
                'status'  =>$request->status == 1 ? 0 : 1,
            ];
            Material::find($request->id)->update($post);
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
}
