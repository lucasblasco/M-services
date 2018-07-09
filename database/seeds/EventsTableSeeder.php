<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
        		'name'=> 'II Workshop LATAM', 
        		'start_date' => Carbon::create(2018, 8, 24)->toDateString(),
				'description' => '#MOVILIDADFUTURA es la llave de presentación y conocimiento de nuevas tecnologías en Seguridad Vial, las Carreteras y el Medio Ambiente. Pensado para establecer encuentros entre Clientes, Socios Claves, Científicos e Inversores, la jornada plantea opciones Conference, Workshop y Summit con el objetivo de brindarle al Asistente la mejor experiencia de presentación.',
				'days_duration' => 1,
				'hours_day' => 6,
				'start_hour' => Carbon::createFromTime(9, 0, 0)->toTimeString(),
				'event_city_id' => 1,
				'event_province_id' => 1,
				'event_country_id' => 1,
				'event_place' => 'PUCV',
				'include_nearby_places' => 0,
				'number_of_attendees' => 500,
				'number_of_rooms' => 1,
				'assistant_activities_id' => 1,
				'include_logo' => 1,
				'include_slide' => 1,
				'include_screen' => 1,
				'include_banners' => 1,
				'include_flyers' => 1,
				'send_invitations_by_mail' => 1,
				'analitycs_segment_audience' => 1,
				'analitycs_inbound_marketing' => 1,
				'analitycs_analyze_scenarios' => 1,
				'analitycs_incident_monitoring' => 1,
				'analitycs_analyze_results' => 1,
				'status_id' => 1,
				'user_id' => 1
		]);
    }
}
