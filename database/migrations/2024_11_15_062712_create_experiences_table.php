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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('position');
            $table->enum('job_type',['full_time','part_time','self_employed','freelancer','contract','internship','seasonal']);
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->enum('location_type',['on_site','hybrid','remote']);
            $table->text('responsibility')->nullable();
            $table->text('job_report')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
