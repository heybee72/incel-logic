<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('profile_image')->nullable();
            $table->string('username')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('business_address')->nullable();
            $table->string('branches')->nullable();
            $table->enum('approved', ['no','yes'])->default('no');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->string('api_token', 60)->unique();
            $table->rememberToken('name');
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
        Schema::dropIfExists('agents');
    }
}
