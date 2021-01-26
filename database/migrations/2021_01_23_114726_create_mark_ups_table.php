<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mark_ups', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->nullable();
            $table->string('markup')->nullable();
            $table->string('agent_absolute_value')->nullable();
            $table->string('agent_percentage_value')->nullable();
            $table->string('customer_absolute_value')->nullable();
            $table->string('customer_percentage_value')->nullable();
            $table->enum('agent_selected', ['absolute_value', 'percentage'])->default('absolute_value');
            $table->enum('customer_selected', ['absolute_value', 'percentage'])->default('absolute_value');

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
        Schema::dropIfExists('mark_ups');
    }
}
