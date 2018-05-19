<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->datetime('start_date');
            $table->text('description');
            $table->integer('days_duration');
            $table->integer('hours_day');
            $table->integer('start_hour');
            $table->integer('event_city_id')->unsigned();
            $table->integer('event_province_id')->unsigned()->nullable();
            $table->integer('event_country_id')->unsigned();
            $table->string('event_place');
            $table->boolean('include_nearby_places');
            $table->integer('number_of_attendees');
            $table->integer('assistant_activities_id')->unsigned();
            $table->boolean('logo');
            $table->boolean('slide');
            $table->boolean('screen');
            $table->boolean('banners');
            $table->boolean('flyers');
            $table->boolean('send_invitations_by_mail');
            $table->boolean('analitycs_segment_audience');
            $table->boolean('analitycs_inbound_marketing');
            $table->boolean('analitycs_analyze_scenarios');
            $table->boolean('analitycs_incident_monitoring');
            $table->boolean('analitycs_analyze_results');
            $table->foreign('event_city_id')->references('id')->on('cities');
            $table->foreign('event_province_id')->references('id')->on('provinces');
            $table->foreign('event_country_id')->references('id')->on('countries');
            $table->foreign('assistant_activities_id')->references('id')->on('assistant_activities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
