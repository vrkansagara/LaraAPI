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
        $adminName = config('app.name');
        $adminEmail = config('app.email');

        $user =  \App\Entities\User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'is_active' => 1,
            'is_confirm' => 1,
            'is_term_agree' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'password' => Hash::make($adminEmail),
        ]);
    }
}
