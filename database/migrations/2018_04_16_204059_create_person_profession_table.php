<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonProfessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_profession', function (Blueprint $table) {
            $table->increments('id');
             $table->integer('person_id')->unsigned();
            $table->integer('profession_id')->unsigned();
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('persons');
            $table->foreign('profession_id')->references('id')->on('professions');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_profession');
    }
}
