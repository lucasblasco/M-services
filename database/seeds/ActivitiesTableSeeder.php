<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')->insert([
            'name' => 'COFFEE MEET', 
            'description' => '15´ para presentarse con el asistente elegido',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 5,
            'day' => 1,
            'start_time' => Carbon::createFromTime(9, 0, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(9, 15, 0)->toTimeString(),
            'status_id' => 1
        ]);
        DB::table('activities')->insert([
            'name' => 'APERTURA', 
            'description' => 'Presentación de II Workshop LATAM',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 7,
            'day' => 1,
            'start_time' => Carbon::createFromTime(9, 15, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(9, 45, 0)->toTimeString(),
            'status_id' => 1
        ]);
        DB::table('activities')->insert([
            'name' => 'CONGRESS', 
            'description' => 'Disertación a cargo de influenciadores',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 1,
            'day' => 1,
            'start_time' => Carbon::createFromTime(9, 45, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(10, 45, 0)->toTimeString(),
            'status_id' => 1
        ]);
        DB::table('activities')->insert([
            'name' => 'WORKSHOP', 
            'description' => 'Resolución de problemáticas junto al panel de expertos',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 2,
            'day' => 1,
            'start_time' => Carbon::createFromTime(10, 45, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(12, 45, 0)->toTimeString(),
            'status_id' => 1
        ]);
        DB::table('activities')->insert([
            'name' => 'BREAK', 
            'description' => '',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 8,
            'day' => 1,
            'start_time' => Carbon::createFromTime(12, 45, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(13, 30, 0)->toTimeString(),
            'status_id' => 1
        ]);
        DB::table('activities')->insert([
            'name' => 'SUMMIT', 
            'description' => 'Espacio de 10´ para presentar tu idea o prototipo a posibles socios clave',
            'event_id' => 1,
            'room_id' => 1,
            'event_format_id' => 3,
            'day' => 1,
            'start_time' => Carbon::createFromTime(13, 30, 0)->toTimeString(),
            'end_time' => Carbon::createFromTime(15, 00, 0)->toTimeString(),
            'status_id' => 1
        ]);
    }
}
