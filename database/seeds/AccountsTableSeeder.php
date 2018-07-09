<?php

use Illuminate\Database\Seeder;
use App\Account;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert(['name'=> 'Facebook', 'image_name' => 'fa fa-facebook']);
        DB::table('accounts')->insert(['name'=> 'Twitter', 'image_name' => 'fa fa-twitter']);
        DB::table('accounts')->insert(['name'=> 'Instagram', 'image_name' => 'fa fa-instagram']);
        DB::table('accounts')->insert(['name'=> 'Youtube', 'image_name' => 'fa fa-youtube']);
        DB::table('accounts')->insert(['name'=> 'Linkedin', 'image_name' => 'fa fa-linkedin']);
    }
}
