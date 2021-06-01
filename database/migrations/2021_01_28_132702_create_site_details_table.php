<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_details', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('address_1');
            $table->string('address_2');
            $table->string('phone_1');
            $table->string('phone_2');
            $table->text('privacy_policy');
            $table->text('service_policy');
            $table->text('refund_policy');
            $table->text('tac');
            $table->string('email');
            $table->string('facebook');
            $table->string('instagram');
            $table->string('twitter');
            $table->text('pintrest');
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
        Schema::dropIfExists('site_details');
    }
}
