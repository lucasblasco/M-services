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
        DB::table('assistant_activities')->insert(['name'=> 'Profesional']);
        DB::table('assistant_activities')->insert(['name'=> 'No Profesional']);
        DB::table('assistant_activities')->insert(['name'=> 'Ambos']);
    }
}
