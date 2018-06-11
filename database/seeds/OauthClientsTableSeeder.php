<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('oauth_clients')->insert([
        	'name'=> 'angular', 
        	'secret'=> 'eudaqO1iymV1Oo9AsMudvrm08GYvmbExAbzD3IRj', 
        	'redirect' => 'http://localhost', 
        	'personal_access_client' => '0', 
        	'password_client' => '1', 
        	'revoked' => '0'
        ]);
    }
}
