<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Room::class, 25)->create();
    }
}
