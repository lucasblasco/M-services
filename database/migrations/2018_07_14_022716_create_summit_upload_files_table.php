<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummitUploadFilesTable extends Migration
{
    public function up()
    {
        Schema::create('summit_upload_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('user_id')->unsigned();
            $table->string('activity_id')->unsigned();
            $table->string('template_path');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('activity_id')->references('id')->on('activities'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('summit_upload_files');
    }
}
