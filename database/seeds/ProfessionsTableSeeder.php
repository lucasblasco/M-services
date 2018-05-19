<?php

use Illuminate\Database\Seeder;
use App\Profession;

class ProfessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Profession::class, 25)->create();
    }
}
