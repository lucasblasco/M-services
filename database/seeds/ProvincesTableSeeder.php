<?php

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert(['name'=> 'Buenos Aires' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Buenos Aires-GBA' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Capital Federal' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Catamarca' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Chaco' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Chubut' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Córdoba' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Corrientes' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Entre Ríos' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Formosa' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Jujuy' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'La Pampa' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'La Rioja' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Mendoza' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Misiones' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Neuquén' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Río Negro' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Salta' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'San Juan' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'San Luis' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Santa Cruz' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Santa Fe' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Santiago del Estero' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Tierra del Fuego' , 'country_id' => 1]);
		DB::table('provinces')->insert(['name'=> 'Tucumán' , 'country_id' => 1]);
    }
}
