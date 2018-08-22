<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commonOperation = ['list','view','create','update','delete','activate','inactivate'];
        $commonModule = ['user','role','permission'];

        $permissions = [];

        foreach ($commonModule as $k => $moduleName){
            foreach ($commonOperation as $kk => $operationName){
                $permissions[] = [
                    'name' => $operationName.'-'.$moduleName,
                    'status' => 1,
                    'guard_name' => 'api',
                ];
            }
        }

        try {
            DB::beginTransaction();
            foreach ($permissions as $permission) {
                Permission::create($permission);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
