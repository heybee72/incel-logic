<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisaApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable();
            $table->integer('visa_type_id')->nullable();

            $table->string('home_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->text('remark')->nullable();

            $table->integer('traveller_id')->nullable();
            $table->integer('agent_id')->nullable();
            
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
        Schema::dropIfExists('visa_applications');
    }
}
