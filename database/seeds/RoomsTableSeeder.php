<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('rooms')->insert(['name'=> 'Sala principal', 'description' => 'Sala principal', 'capacity' => 1000]);
    }
}
