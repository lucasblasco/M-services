<?php

use Illuminate\Database\Seeder;
use App\AgeRange;

class AgeRangesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AgeRange::class, 4)->create();
    }
}
