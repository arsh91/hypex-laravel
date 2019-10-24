<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'first_name' => 'Test',
            'last_name' => 'test',
            'user_name' => 'test',
            'email' => 'test@yopmail.com',
            'phone' => '1234567892',
            'is_admin' => '0',
            'status' => '1',
            'password' => Hash::make('123456789'),
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);
    }
}
