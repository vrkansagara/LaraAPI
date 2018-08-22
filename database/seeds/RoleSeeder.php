<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'supper-admin',
                'guard_name' => 'api',
                'status' => 1
            ],
            [
                'name' => 'admin',
                'guard_name' => 'api',
                'status' => 1
            ],
            [
                'name' => 'manager',
                'guard_name' => 'api',
                'status' => 1
            ],
            [
                'name' => 'executive',
                'guard_name' => 'api',
                'status' => 1
            ],
            [
                'name' => 'guest',
                'guard_name' => 'api',
                'status' => 1
            ]
        ];

        try{
            DB::beginTransaction();
            foreach ($roles as $role) {
                Role::create($role);
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }

//        $permission = Permission::create(['name' => 'edit articles']);
    }
}
