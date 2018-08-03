<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoffeeInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffee_invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_activity_user_send')->unsigned();
            $table->integer('id_user_receive')->unsigned();
            $table->integer('id_status_coffee')->unsigned();
            $table->foreign('id_activity_user_send')->references('id')->on('activity_user');
            $table->foreign('id_user_receive')->references('id')->on('users');
            $table->foreign('id_status_coffee')->references('id')->on('status_coffee');
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
        Schema::dropIfExists('coffee_invitations');
    }
}
