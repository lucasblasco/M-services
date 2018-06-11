<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nulleable();
            $table->integer('event_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->integer('event_format_id')->unsigned();
            $table->integer('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('status_id')->default(1)->unsigned();
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('room_id')->references('id')->on('rooms');   
            $table->foreign('event_format_id')->references('id')->on('event_formats');
            $table->foreign('status_id')->references('id')->on('statuses');   
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
        Schema::dropIfExists('activities');
    }
}
