<?php

use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
	Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('/');
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// Setting
		// Menus
			Route::get('/menu', 'App\Http\Controllers\Setting\MenusController@index')->name('menu');
			Route::get('getMenus', 'App\Http\Controllers\Setting\MenusController@getMenus')->name('getMenus');
			Route::get('getActiveMenus', 'App\Http\Controllers\Setting\MenusController@getActiveMenus')->name('getActiveMenus');
			Route::get('getSubMenus', 'App\Http\Controllers\Setting\MenusController@getSubMenus')->name('getSubMenus');
			Route::post('addMenus', 'App\Http\Controllers\Setting\MenusController@addMenus')->name('addMenus');
			Route::post('addSubmenus', 'App\Http\Controllers\Setting\MenusController@addSubmenus')->name('addSubmenus');
			Route::post('updateSubmenus', 'App\Http\Controllers\Setting\MenusController@updateSubmenus')->name('updateSubmenus');
		// Menus

		// Role Permission
			Route::get('/role_permission', 'App\Http\Controllers\Setting\RolePermissionController@index')->name('role_permission');
			Route::get('getPermission', 'App\Http\Controllers\Setting\RolePermissionController@getPermission')->name('getPermission');
			Route::get('getRole', 'App\Http\Controllers\Setting\RolePermissionController@getRole')->name('getRole');
			Route::get('permissionMenus', 'App\Http\Controllers\Setting\RolePermissionController@permissionMenus')->name('permissionMenus');
			Route::post('addRole', 'App\Http\Controllers\Setting\RolePermissionController@addRole')->name('addRole');
			Route::get('detailRole', 'App\Http\Controllers\Setting\RolePermissionController@detailRole')->name('detailRole');
			Route::post('updateRole', 'App\Http\Controllers\Setting\RolePermissionController@updateRole')->name('updateRole');
			Route::post('savePermission', 'App\Http\Controllers\Setting\RolePermissionController@savePermission')->name('savePermission');
			Route::get('deleteRole', 'App\Http\Controllers\Setting\RolePermissionController@deleteRole')->name('deleteRole');
			Route::get('deletePermission', 'App\Http\Controllers\Setting\RolePermissionController@deletePermission')->name('deletePermission');
		// Role Permission

		// User Access
		
		Route::get('/user_access', 'App\Http\Controllers\Setting\UserAccessController@index')->name('user_access');
		Route::get('getRoleUser', 'App\Http\Controllers\Setting\UserAccessController@getRoleUser')->name('getRoleUser');
		Route::get('getUser', 'App\Http\Controllers\Setting\UserAccessController@getUser')->name('getUser');
		Route::get('getUserDepartment', 'App\Http\Controllers\Setting\UserAccessController@getUserDepartment')->name('getUserDepartment');
		Route::get('getDepartment', 'App\Http\Controllers\Setting\UserAccessController@getDepartment')->name('getDepartment');
		Route::post('addRoleUser', 'App\Http\Controllers\Setting\UserAccessController@addRoleUser')->name('addRoleUser');
		Route::get('detailRoleUser', 'App\Http\Controllers\Setting\UserAccessController@detailRoleUser')->name('detailRoleUser');
		Route::post('updateRoleUser', 'App\Http\Controllers\Setting\UserAccessController@updateRoleUser')->name('updateRoleUser');
		Route::get('getRolePermissionDetail', 'App\Http\Controllers\Setting\UserAccessController@getRolePermissionDetail')->name('getRolePermissionDetail');
		Route::post('saveRolePermission', 'App\Http\Controllers\Setting\UserAccessController@saveRolePermission')->name('saveRolePermission');
		Route::get('destroyRolePermission', 'App\Http\Controllers\Setting\UserAccessController@destroyRolePermission')->name('destroyRolePermission');
		// User Access

		// Approval	
		
		Route::get('/approval', 'App\Http\Controllers\Setting\ApprovalController@index')->name('approval');
		Route::get('/getApprovalHeader', 'App\Http\Controllers\Setting\ApprovalController@getApprovalHeader')->name('getApprovalHeader');
		Route::post('/addApprovalHeader', 'App\Http\Controllers\Setting\ApprovalController@addApprovalHeader')->name('addApprovalHeader');
		Route::get('/getStepApproval', 'App\Http\Controllers\Setting\ApprovalController@getStepApproval')->name('getStepApproval');
		Route::post('/updateApproval', 'App\Http\Controllers\Setting\ApprovalController@updateApproval')->name('updateApproval');
		Route::get('/getApprovalList', 'App\Http\Controllers\Setting\ApprovalController@getApprovalList')->name('getApprovalList');
		// Approval


	// Setting

	// Master
			Route::get('/master_product', 'App\Http\Controllers\Master\MasterProductController@index')->name('master_product');
			Route::get('/getMasterProduct', 'App\Http\Controllers\Master\MasterProductController@getMasterProduct')->name('getMasterProduct');
			Route::post('/uploadMasterProduct', 'App\Http\Controllers\Master\MasterProductController@uploadMasterProduct')->name('uploadMasterProduct');
			Route::get('/getDetailProductLog', 'App\Http\Controllers\Master\MasterProductController@getDetailProductLog')->name('getDetailProductLog');
			Route::post('/updateProduct', 'App\Http\Controllers\Master\MasterProductController@updateProduct')->name('updateProduct');
			
			Route::get('/category_machine', 'App\Http\Controllers\Master\CategoryMachineController@index')->name('category_machine');
			Route::get('/getCategoryMachine', 'App\Http\Controllers\Master\CategoryMachineController@getCategoryMachine')->name('getCategoryMachine');
			Route::post('/addCategoryMachine', 'App\Http\Controllers\Master\CategoryMachineController@addCategoryMachine')->name('addCategoryMachine');
			Route::post('/updateCategoryMachine', 'App\Http\Controllers\Master\CategoryMachineController@updateCategoryMachine')->name('updateCategoryMachine');
			
			Route::get('/master_machine', 'App\Http\Controllers\Master\MasterMachineController@index')->name('master_machine');
			Route::get('/getMachine', 'App\Http\Controllers\Master\MasterMachineController@getMachine')->name('getMachine');
			Route::get('/getLocation', 'App\Http\Controllers\Master\MasterMachineController@getLocation')->name('getLocation');
			Route::get('/getActiveMachine', 'App\Http\Controllers\Master\MasterMachineController@getActiveMachine')->name('getActiveMachine');
			Route::post('/addMachine', 'App\Http\Controllers\Master\MasterMachineController@addMachine')->name('addMachine');
			Route::post('/udpateStatusMachine', 'App\Http\Controllers\Master\MasterMachineController@udpateStatusMachine')->name('udpateStatusMachine');
			Route::post('/updateMachine', 'App\Http\Controllers\Master\MasterMachineController@updateMachine')->name('updateMachine');


			Route::get('/material', 'App\Http\Controllers\Master\MaterialController@index')->name('material');
			Route::get('/getMaterial', 'App\Http\Controllers\Master\MaterialController@getMaterial')->name('getMaterial');
			Route::get('/getActiveMaterial', 'App\Http\Controllers\Master\MaterialController@getActiveMaterial')->name('getActiveMaterial');
			Route::post('/addMaterial', 'App\Http\Controllers\Master\MaterialController@addMaterial')->name('addMaterial');
			Route::post('/updateMaterial', 'App\Http\Controllers\Master\MaterialController@updateMaterial')->name('updateMaterial');
			Route::post('/udpateStatusMaterial', 'App\Http\Controllers\Master\MaterialController@udpateStatusMaterial')->name('udpateStatusMaterial');
	// Master

	// Transaction	
	
	Route::get('/inspection-goods', 'App\Http\Controllers\Transaction\Inspection\GoodsController@index')->name('inspection-goods');
	Route::get('/getInspectionGoods', 'App\Http\Controllers\Transaction\Inspection\GoodsController@getInspectionGoods')->name('getInspectionGoods');
	Route::post('/addInspectionGoodsHeader', 'App\Http\Controllers\Transaction\Inspection\GoodsController@addInspectionGoodsHeader')->name('addInspectionGoodsHeader');
	Route::get('/getInspectionGoodShift', 'App\Http\Controllers\Transaction\Inspection\GoodsController@getInspectionGoodShift')->name('getInspectionGoodShift');
	Route::post('/addSubDetailInspectionGoods', 'App\Http\Controllers\Transaction\Inspection\GoodsController@addSubDetailInspectionGoods')->name('addSubDetailInspectionGoods');
	Route::post('/lockInspectionGoods', 'App\Http\Controllers\Transaction\Inspection\GoodsController@lockInspectionGoods')->name('lockInspectionGoods');
	Route::get('/mappingInspectionProgress', 'App\Http\Controllers\Transaction\Inspection\GoodsController@mappingInspectionProgress')->name('mappingInspectionProgress');
	Route::get('/detailInspectionProgress', 'App\Http\Controllers\Transaction\Inspection\GoodsController@detailInspectionProgress')->name('detailInspectionProgress');
	Route::get('/getInspectionGoodSubDetailItem', 'App\Http\Controllers\Transaction\Inspection\GoodsController@getInspectionGoodSubDetailItem')->name('getInspectionGoodSubDetailItem');
	Route::post('/updateInspectionGoodSubDetail', 'App\Http\Controllers\Transaction\Inspection\GoodsController@updateInspectionGoodSubDetail')->name('updateInspectionGoodSubDetail');
	Route::get('/getWallThickness', 'App\Http\Controllers\Transaction\Inspection\GoodsController@getWallThickness')->name('getWallThickness');
	Route::post('/updateWallThickness', 'App\Http\Controllers\Transaction\Inspection\GoodsController@updateWallThickness')->name('updateWallThickness');
	Route::post('/addWallThickness', 'App\Http\Controllers\Transaction\Inspection\GoodsController@addWallThickness')->name('addWallThickness');
	
	Route::get('exportInspectionGoodProgress/{id}','App\Http\Controllers\Transaction\Inspection\GoodsController@exportInspectionGoodProgress');

	// Approval
	Route::get('/getApprovalUser', 'App\Http\Controllers\Setting\ApprovalController@getApprovalUser')->name('getApprovalUser');
		Route::post('/updateInspectionGoodsApproval', 'App\Http\Controllers\Setting\ApprovalController@updateInspectionGoodsApproval')->name('updateInspectionGoodsApproval');
	// Approval

		// Material
		
		Route::get('/inspection-material', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@index')->name('inspection-material');
		Route::get('/getInspectionMaterial', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@getInspectionMaterial')->name('getInspectionMaterial');
		Route::get('/getSupplier', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@getSupplier')->name('getSupplier');
		Route::post('/addInspectionMaterial', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@addInspectionMaterial')->name('addInspectionMaterial');
		Route::get('/getResinInspection', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@getResinInspection')->name('getResinInspection');
		Route::post('/updateResinDetail', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@updateResinDetail')->name('updateResinDetail');
		Route::post('/updateResinProperties', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@updateResinProperties')->name('updateResinProperties');
		Route::get('/getPelletInspection', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@getPelletInspection')->name('getPelletInspection');
		Route::post('/updatePelletProperties', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@updatePelletProperties')->name('updatePelletProperties');
		Route::post('/lockInspectionMaterial', 'App\Http\Controllers\Transaction\Inspection\InspectionMaterialController@lockInspectionMaterial')->name('lockInspectionMaterial');

		// Material
	// Transaction

});

