<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'user_name' => 'admin',
            'email' => 'admin@yopmail.com',
            'phone' => '9876543212',
            'is_admin' => '1',
            'status' => '1',
            'password' => Hash::make('123456789'),
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);
    }
}
