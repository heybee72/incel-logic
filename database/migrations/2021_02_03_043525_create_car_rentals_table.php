<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_location')->nullable();
            $table->string('user_id')->nullable();
            $table->string('traveller_id')->nullable();
            $table->string('agent_id')->nullable();
            $table->string('destination')->nullable();
            $table->string('no_of_passengers')->nullable();
            $table->string('car_type')->nullable();
            $table->enum('journey_type', ['single_journey', 'roundtrip', 'all_day'])->nullable();
            $table->string('cost')->nullable();
            $table->enum('payment_status', ['not-paid','paid'])->default('not-paid');
            $table->string('pickup_date')->nullable();
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
        Schema::dropIfExists('car_rentals');
    }
}
