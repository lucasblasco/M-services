<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->datetime('birth_date');
            $table->integer('document_type_id')->unsigned();
            $table->integer('study_level_id')->unsigned()->nullable();
            $table->string('document_number')->unique();
            $table->string('phone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('email')->unique();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->foreign('study_level_id')->references('id')->on('study_levels');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
