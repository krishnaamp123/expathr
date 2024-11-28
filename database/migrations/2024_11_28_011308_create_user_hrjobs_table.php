<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_hrjobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_job');
            $table->enum('status',['applicant','shortlist','phone_screen','hr_interview','user_interview','skill_test','reference_check','offering','rejected','hired'])->default('applicant');
            $table->bigInteger('salary_expectation')->unsigned();
            $table->enum('availability',['immediately','<1_month_notice','1_month_notice','>1_month_notice']);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_job')->references('id')->on('hrjobs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_hrjobs');
    }
};
