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
        Schema::table('job_experiences', function (Blueprint $table) {
            $table->string('job_type')->nullable();
            $table->string('job_mode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobexperience', function (Blueprint $table) {
            $table->dropColumn(['job_mode', 'job_type']);
        });
    }
};
