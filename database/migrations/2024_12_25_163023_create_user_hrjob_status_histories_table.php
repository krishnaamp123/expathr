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
        Schema::create('user_hrjob_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_hrjob_id');
            $table->enum('status', [
                'applicant', 'shortlist', 'phone_screen', 'hr_interview',
                'user_interview', 'skill_test', 'reference_check',
                'offering', 'rejected', 'hired'
            ]);
            $table->timestamps();

            // Foreign key
            $table->foreign('user_hrjob_id')->references('id')->on('user_hrjobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_hrjob_status_histories');
    }
};
