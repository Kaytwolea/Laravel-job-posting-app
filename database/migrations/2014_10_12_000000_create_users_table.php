<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name')->nullable(false);
            $table->string('last_name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->boolean('isAdmin')->default('0');
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('confirmation_code');
            $table->string('reset_code')->nullable();
            $table->boolean('kyc')->default(0);
            $table->string('cv')->nullable();
            $table->string('skills')->nullable();
            $table->longText('about')->nullable();
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
        Schema::dropIfExists('users');
    }
};
