<?php

use Illuminate\Database\Seeder;
use App\StudyLevel;

class StudyLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(StudyLevel::class, 4)->create();
    }
}
