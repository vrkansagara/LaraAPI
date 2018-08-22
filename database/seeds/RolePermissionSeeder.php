<?php

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();

        $allowAllPermissionsToRoles = ['supper-admin'];
        try {
            DB::beginTransaction();
            foreach ($roles as $role) {
                if (in_array($role->getAttribute('name'), $allowAllPermissionsToRoles)) {
                    // Add all permissions
                    foreach ($permissions as $k => $permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
