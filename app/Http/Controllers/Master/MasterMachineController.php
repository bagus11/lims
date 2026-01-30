<?php

namespace App\Http\Controllers\Master;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Machine\StoreMachineRequest;
use App\Http\Requests\Master\Machine\UpdateMachineRequest;
use App\Models\Master\Machine;
use App\Models\Master\MasterLocation;
use Illuminate\Http\Request;

class MasterMachineController extends Controller
{
    function index() {
        return view('master.master_machine.master_machine-index');
    }
    function getMachine() {
        $data = Machine::with('categoryRelation')->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getLocation() {
        $data = MasterLocation::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getActiveMachine() {
        $data = Machine::where('status',1)->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function addMachine(Request $request, StoreMachineRequest $storeMachineRequest) {
                  // try {
                    $storeMachineRequest->validated();
                    $post = [
                        'name'  =>$request->name,
                        'category_id'  =>$request->category_id,
                        'no_machine'  =>$request->no_machine,
                        'status'  =>1,
                        'description'  =>$request->description,
                    ];
                    Machine::create($post);
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
    function udpateStatusMachine(Request $request) {
                  // try {
                    $post = [
                        'status'  =>$request->status == 1 ? 0 : 1,
                    ];
                    Machine::find($request->id)->update($post);
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
    function updateMachine(Request $request, UpdateMachineRequest $updateMachineRequest) {
                  // try {
                    $updateMachineRequest->validated();
                    $post = [
                        'name'  =>$request->name_edit,
                        'category_id'  =>$request->category_id_edit,
                        'no_machine'  =>$request->no_machine_edit,
                        'description'  =>$request->description_edit,
                    ];
                    Machine::find($request->id)->update($post);
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
