<?php

namespace App\Http\Controllers\Master;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CategoryMachine\StoreCategoryMachineRequest;
use App\Http\Requests\Master\CategoryMachine\UpdateCategoryMachineRequest;
use App\Models\Master\CategoryMachine;
use Illuminate\Http\Request;

class CategoryMachineController extends Controller
{
    function index() {
        return view('master.category_machine.category_machine-index');
    }
    function getCategoryMachine() {
        $data = CategoryMachine::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function addCategoryMachine(Request $request, StoreCategoryMachineRequest $storeCategoryMachineRequest ) {
          // try {
            $storeCategoryMachineRequest->validated();
            $post = [
                'name'  =>$request->name,
                'description'  =>$request->description,
            ];
            CategoryMachine::create($post);
            return ResponseFormatter::success(
               $post,
                'Category successfully added'
            );            
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Category failed to update',
        //         500
        //     );
        // }
    }
    function updateCategoryMachine(Request $request, UpdateCategoryMachineRequest $storeCategoryMachineRequest ) {
          // try {
            $storeCategoryMachineRequest->validated();
            $post = [
                'name'  =>$request->name_edit,
                'description'  =>$request->description_edit,
            ];
            CategoryMachine::find($request->id)->update($post);
            return ResponseFormatter::success(
               $post,
                'Category successfully updated'
            );            
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Category failed to update',
        //         500
        //     );
        // }
    }
}
