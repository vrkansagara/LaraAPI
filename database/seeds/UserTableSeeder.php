<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminName = env('ADMIN_NAME');

        return \App\User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'is_status' => 1,
            'is_confirm' => 1,
            'is_term_accept' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'password' => Hash::make($adminEmail),
        ]);
    }
}
