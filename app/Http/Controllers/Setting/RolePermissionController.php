<?php

namespace App\Http\Controllers\Setting;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\RolePermisison\UpdateRolesRequest;
use App\Http\Requests\Setting\RolePermission\AddRolesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    function  index() {
        return view('setting.role_permission.role_permission-index');
    }
    function getPermission() {
        $data = Permission::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getRole() {
        $data = Role::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    public function permissionMenus(){
        $data = DB::table('view_menus')
                    ->select('*')
                    ->get();
        return response()->json([
            'menus_name'=>$data,
        ]);
    }
    function addRole(Request $request, AddRolesRequest $addRolesRequest) {
        // try {
            $addRolesRequest->validated();
            $post=[
                'name'=>$request->roles_name,
                'guard_name'=>'web',
            ];
            Role::create($post);
            return ResponseFormatter::success(
                $post,
                'Roles successfully added'
            );            
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error(
        //         $th,
        //         'Roles failed to add',
        //         500
        //     );
        // }
    }
    function detailRole(Request $request) {
        $detail = Role::find($request->id);
        return response()->json([
            'detail'=>$detail,
        ]);
    }
    function updateRole(Request $request, UpdateRolesRequest $updateRolesRequest) {
        try {
            $updateRolesRequest->validated();
            $post=[
                'name'=>$request->roles_name_edit,
                'guard_name'=>'web',
            ];
            Role::find($request->id)->update($post);
            return ResponseFormatter::success(
                $post,
                'Roles successfully added'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Roles failed to add',
                500
            );
        }
    }
    public function deleteRole(Request $request)
    {
        $status     = 500;
        $message    = 'Role failed to delete, please call Developer';
        $delete     = Role::find($request->id)->delete();
        if($delete){
            $status =200;
            $message ='Data successfully deleted';
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
    }
    
    function savePermission(Request $request) {
        $permission_name = $request->permission_name;
        $status=500;
        $message="Data failed to save";
        $validator = Validator::make($request->all(),[
            'permission_name'=>'required|unique:permissions,name',
        ],[
            'permission_name.required'=>'Permission tidak boleh kosong',
            'permission_name.unique'=>'Permission sudah ada',
        
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>$validator->errors(), 
                'status'=>422
            ]);
        }else{
            $post=[
                'name'=>$permission_name,
                'guard_name'=>'web',
            ];
         
            $insert = Permission::create($post);
            if($insert){
                $status=200;
                $message="Data successfully inserted";
            }
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
    }
    public function deletePermission(Request $request)
    {
        $status =200;
        $message ='Data failed to delete';
        $delete = Permission::find($request->id);
        $delete->delete();
        if($delete){
            $message ="Data successfully deleted";
            $status =200;
        }
          return response()->json([
            'message'=>$message,
            'status'=>$status,
        ]);
    }

}
