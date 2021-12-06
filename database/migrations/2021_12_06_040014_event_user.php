<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->nullable(); 
            $table->foreign('event_id')->references('id')->on('events');
            $table->unsignedInteger('user_id')->nullable(); 
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('event_users');
    }
}
