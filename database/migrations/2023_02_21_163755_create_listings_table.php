<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->json('tags');
            $table->string('company_name');
            $table->json('skills');
            $table->string('job_type');
            $table->boolean('open')->default('1');
            $table->string('location');
            $table->string('email');
            $table->string('website');
            $table->longText('description');
            $table->integer('views')->default('0');
            $table->boolean('accepted')->default('0');
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
        Schema::dropIfExists('listings');
    }
};
