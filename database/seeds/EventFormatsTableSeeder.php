<?php

use Illuminate\Database\Seeder;
use App\EventFormat;

class EventFormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_formats')->insert(['name'=> 'M_Congress', 'duration' => 60]);
        DB::table('event_formats')->insert(['name'=> 'M_Workshop', 'duration' => 180]);
        DB::table('event_formats')->insert(['name'=> 'M_Summit', 'duration' => 180]);
        DB::table('event_formats')->insert(['name'=> 'M_Hackathon', 'duration' => 0]);
        DB::table('event_formats')->insert(['name'=> 'M_Coffee', 'duration' => 15]);
        DB::table('event_formats')->insert(['name'=> 'Acreditación', 'duration' => 60]);
        DB::table('event_formats')->insert(['name'=> 'Apertura', 'duration' => 60]);
        DB::table('event_formats')->insert(['name'=> 'Break', 'duration' => 30]);
    }
}
