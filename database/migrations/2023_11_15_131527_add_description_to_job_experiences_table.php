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
            $table->longText('job_description')->nullable();
            $table->enum('end_date_type', ['present', 'specific_date'])->default('specific_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_experiences', function (Blueprint $table) {
            $table->dropColumn(['job_description', 'end_date_type']);
        });
    }
};
