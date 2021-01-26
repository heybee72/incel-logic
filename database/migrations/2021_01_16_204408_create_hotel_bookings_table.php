<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->enum('booked_by', ['user','agent'])->nullable();
            $table->string('rooms')->nullable();
            $table->string('no_adult')->nullable();
            $table->string('no_children')->nullable();
            $table->string('residency')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('traveller_id')->nullable();
            $table->integer('agent_id')->nullable();


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
        Schema::dropIfExists('hotel_bookings');
    }
}
