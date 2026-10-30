<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RegisterLaboratoryNavigation extends Migration
{
    public function up()
    {
        $now = now();
        $masterMenuId = DB::table('menus')->where('link', 'master')->value('id');
        $transactionMenuId = DB::table('menus')->where('link', 'transaction')->value('id');

        DB::table('permissions')->updateOrInsert(
            ['name' => 'view-master_laboratory', 'guard_name' => 'web'],
            ['updated_at' => $now, 'created_at' => $now]
        );

        if ($masterMenuId) {
            DB::table('submenus')->updateOrInsert(
                ['link' => 'master_laboratory'],
                [
                    'name' => 'Master Lab',
                    'permission_name' => 'view-master_laboratory',
                    'status' => 1,
                    'icon' => 'fa-solid fa-flask-vial',
                    'id_menu' => $masterMenuId,
                    'order' => 0,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $masterLaboratoryPermissionId = DB::table('permissions')
            ->where('name', 'view-master_laboratory')
            ->where('guard_name', 'web')
            ->value('id');
        $masterProductPermissionId = DB::table('permissions')
            ->where('name', 'view-master_product')
            ->where('guard_name', 'web')
            ->value('id');

        if ($masterLaboratoryPermissionId && $masterProductPermissionId) {
            $roleIds = DB::table('role_has_permissions')
                ->where('permission_id', $masterProductPermissionId)
                ->pluck('role_id');

            foreach ($roleIds as $roleId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $masterLaboratoryPermissionId,
                    'role_id' => $roleId,
                ]);
            }
        }

        if ($transactionMenuId && !DB::table('submenus')->where('link', 'lab')->exists()) {
            DB::table('permissions')->updateOrInsert(
                ['name' => 'view-lab', 'guard_name' => 'web'],
                ['updated_at' => $now, 'created_at' => $now]
            );

            DB::table('submenus')->insert([
                'name' => 'Laboratory',
                'permission_name' => 'view-lab',
                'status' => 1,
                'icon' => 'fa-solid fa-flask',
                'id_menu' => $transactionMenuId,
                'link' => 'lab',
                'order' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        $permissionId = DB::table('permissions')
            ->where('name', 'view-master_laboratory')
            ->where('guard_name', 'web')
            ->value('id');

        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        DB::table('submenus')->where('link', 'master_laboratory')->delete();
    }
}
