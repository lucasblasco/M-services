<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert(['name'=> 'Disponible']);
        DB::table('statuses')->insert(['name'=> 'No disponible']);
    }
}
