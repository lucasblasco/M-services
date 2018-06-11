<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
    	DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@mwork.com',
            'password' => bcrypt('admin'),
            'enabled' => true
        ]);        
    }
}