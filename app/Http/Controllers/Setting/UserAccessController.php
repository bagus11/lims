<?php

namespace App\Http\Controllers\Setting;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UserAccess\StoreRoleRequest;
use App\Http\Requests\Setting\UserAccess\UpdateRoleUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserAccessController extends Controller
{
    function index() {
        return view('setting.user_access.user_acces-index');
    }
    function getRoleUser() {
        $roleUser   =   DB::table('model_has_roles')->select('roles.name as rolesName','users.id as user_id', 'users.name as userName')
                            ->join('users', 'users.id','=', 'model_has_roles.model_id')
                            ->join('roles','roles.id','=', 'model_has_roles.role_id')
                            ->get();
        $role       =   Role::all();
                    
        return response()->json([
            'roleUser'=>$roleUser,
            'role'=>$role,
            
        ]);  
    }
    function getUser(){
        $data = User::all();
        return response()->json([
            'data'=>$data,
        ]); 
    }
    function getUserDepartment(){
        $data = User::where('departement', auth()->user()->departement)->get();
        return response()->json([
            'data'=>$data,
        ]); 
    }
    function addRoleUser(Request $request, StoreRoleRequest $storeRoleUserRequest) {
        try {
            $storeRoleUserRequest->validated();
            $user = User::find($request->userId);
            $role = Role::find($request->roleId);
            $user->assignRole($role->name);
            return ResponseFormatter::success(   
                $role->name,                              
                'Role User successfully added'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Role User failed to add',
                500
            );
        }
    }
    function detailRoleUser(Request $request){
        $user_id = $request->id;
        $detail = DB::table('model_has_roles')->select('model_has_roles.*','roles.name as roles_name', 'users.name as user_name')->join('users', 'users.id','=','model_has_roles.model_id')->join('roles', 'roles.id', 'model_has_roles.role_id')->where('users.id', $user_id)->first();
        return response()->json([
            'detail'=>$detail,
        ]); 
    }
    function updateRoleUser(Request $request, UpdateRoleUserRequest $updateRoleUserRequest){
        try {
            $updateRoleUserRequest->validated();
            $user = User::find($request->userIdEdit);
            $role = Role::find($request->roleIdEdit);
            $delete = DB::table('model_has_roles')->where('model_id', $request->userIdEdit)->delete();
            if($delete){
                $user->assignRole($role->name);
            }
            return ResponseFormatter::success(   
                $role->name,                              
                'Roles successfully updated'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Roles failed to add',
                500
            );
        }
    }
    function getRolePermissionDetail(Request $request) {
        $inactive   = Permission::select(DB::raw('id,name,"0" as is_active'))
                                ->whereNotIn('id',DB::table('role_has_permissions')
                                ->select('permission_id')
                                ->where('role_id',$request->id))
                                ->get();
        $active     =DB::table('permissions')
                        ->select('*')
                        ->join('role_has_permissions', 'role_has_permissions.permission_id','=','permissions.id')
                        ->where('role_id',$request->id)
                        ->get();
        return response()->json([
            'inactive'=>$inactive,
            'active'=>$active,
        ]);
    }
    function saveRolePermission(Request $request){
        try {    
            $role = Role::find($request->roleIdPermissionAdd);
            $role->givePermissionTo($request->checkArray);

            return ResponseFormatter::success(   
                $role->name,                              
                'Roles permission successfully updated'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Roles permission failed to add',
                500
            );
        }
    }
    function destroyRolePermission(Request $request){
        $status =500;
        $message ="Role permission failed to update";

        foreach($request->checkArray as $row){
            $delete = DB::table('role_has_permissions')
                            ->where('permission_id',$row)
                            ->where('role_id',$request->roleIdPermissionDelete)
                            ->delete();
            if($delete){
                $status =200;
                $message ="Role permission successfully updated";
            }
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message,
        ]);
       
    }
}
