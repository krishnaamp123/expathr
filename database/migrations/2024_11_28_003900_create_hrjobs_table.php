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
        Schema::create('hrjobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_category');
            $table->string('job_name');
            $table->string('job_image')->nullable();
            $table->enum('job_type',['full_time','part_time','self_employed','freelancer','contract','internship','seasonal']);
            $table->text('job_report')->nullable();
            $table->bigInteger('price')->unsigned();
            $table->text('description')->nullable();
            $table->text('qualification')->nullable();
            $table->enum('location_type',['on_site','hybrid','remote']);
            $table->string('location');
            $table->string('experience_min');
            $table->string('education_min');
            $table->date('expired');
            $table->bigInteger('number_hired')->unsigned();
            $table->enum('is_active',['yes','no']);
            $table->timestamps();

            $table->foreign('id_category')->references('id')->on('hrjob_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrjobs');
    }
};