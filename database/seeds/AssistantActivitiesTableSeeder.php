<?php

use Illuminate\Database\Seeder;
use App\AssistantActivity;

class AssistantActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AssistantActivity::class, 3)->create();
    }
}
