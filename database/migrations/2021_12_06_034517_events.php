<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Events extends Migration
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
            $table->string('summary')->nullable();
            $table->string('event_google_id')->nullable();
            $table->string('description')->nullable();
            $table->string('hangoutLink')->nullable();
            $table->string('htmlLink')->nullable();
            $table->string('kind')->nullable();
            $table->string('location')->nullable();
            $table->string('email_creator')->nullable();
            // $table->unsignedInteger('creator_id')->nullable();
            // $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
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
