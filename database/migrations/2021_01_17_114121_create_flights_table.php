<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('traveller_id')->nullable();
            $table->string('flying_from')->nullable();
            $table->string('flying_to')->nullable();
            $table->string('flight_class')->nullable();
            $table->string('flight_type')->nullable();
            $table->string('departure_date')->nullable();
            $table->string('no_of_passengers')->nullable();
            $table->string('no_of_adult')->nullable();
            $table->string('no_of_children')->nullable();
            $table->integer('agent_id')->nullable();
            $table->string('no_of_infant')->nullable();
            $table->enum('booked_by', ['user','agent'])->nullable();

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
        Schema::dropIfExists('flights');
    }
}
