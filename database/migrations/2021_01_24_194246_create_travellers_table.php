<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travellers', function (Blueprint $table) {
            $table->id();

            $table->integer('agent_id')->nullable();
            $table->string('title')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('dob')->nullable();
            $table->string('yob')->nullable();
            $table->string('mob')->nullable();
            
            $table->string('country')->nullable();
            $table->text('profile_image')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('country_of_issue')->nullable();
            $table->string('date_issue')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('emergency_email')->nullable();
            $table->string('emergency_address')->nullable();
            $table->string('insurance_company')->nullable();
            $table->string('insurance_phone')->nullable();

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
        Schema::dropIfExists('travellers');
    }
}
