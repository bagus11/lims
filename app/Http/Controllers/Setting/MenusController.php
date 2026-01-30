<?php

namespace App\Http\Controllers\Setting;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Menus\AddMenusRequest;
use App\Http\Requests\Setting\Menus\AddSubmenusRequest;
use App\Http\Requests\Setting\Menus\UpdateMenusRequest;
use App\Http\Requests\Setting\Menus\UpdateSubmenusRequest;
use App\Models\Setting\Menus;
use App\Models\Setting\Submenus;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    function index() {
        return view('setting.menus.menus-index');
    }
    function getMenus() {
        $data = Menus::all();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getActiveMenus() {
        $data = Menus::where('status',1)->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    function getSubmenus() {
        $data = Submenus::with('menusRelation')->get();
        return response()->json([
            'data'=>$data,
        ]);
    }
    public function addMenus(Request $request,AddMenusRequest $addMenusRequest )
    { 
       
        try {
            $addMenusRequest->validated();
            $post =[
                'name'=>$request->name,
                'link'=>$request->link,
                'icon'=>$request->icon,
                'type'=>$request->type_id,
                'permission_name'=>'view-'.$request->link,
                'status'   => 1
            ];
            Menus::create($post);
            return ResponseFormatter::success(
               $post,
                'Menus successfully added'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Menus failed to update',
                500
            );
        }
    }
    public function addSubmenus(Request $request,AddSubmenusRequest $addMenusRequest )
    { 
       
        try {
            $addMenusRequest->validated();
            $post =[
                'name'=>$request->submenus_name,
                'link'=>$request->submenus_link,
                'icon'=>$request->submenus_icon,
                'permission_name'=>'view-'.$request->submenus_link,
                'order' =>0,
                'id_menu'=>$request->parent_id,
                'status'   => 1
            ];
            Submenus::create($post);
            return ResponseFormatter::success(
               $post,
                'Submenus successfully added'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Submenus failed to update',
                500
            );
        }
    }
    public function updateSubmenus(Request $request,UpdateSubmenusRequest $addMenusRequest )
    { 
       
        try {
            $addMenusRequest->validated();
            $post =[
                'name'=>$request->submenus_name_edit,
                'icon'=>$request->submenus_icon_edit,
                'id_menu'=>$request->parent_id_edit,
            ];
            Submenus::where('link',$request->submenus_link_edit)->update($post);
            return ResponseFormatter::success(
               $post,
                'Submenus successfully updated'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Submenus failed to update',
                500
            );
        }
    }
    public function udpateMenus(Request $request,UpdateMenusRequest $addMenusRequest )
    { 
       
        try {
            $addMenusRequest->validated();
            $post =[
                'name'=>$request->name_edit,
                'link'=>$request->link_edit,
                'icon'=>$request->icon_edit,
                'type'=>$request->type_edit,
                'status'=> $request->status
            ];
            Menus::find($request->id)->update($post);
            return ResponseFormatter::success(
               $post,
                'Menus successfully added'
            );            
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                $th,
                'Menus failed to update',
                500
            );
        }
    }
}
