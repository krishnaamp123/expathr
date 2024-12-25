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
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_outlet');
            $table->unsignedBigInteger('id_city');
            $table->string('job_name');
            $table->enum('job_type',['full_time','part_time','self_employed','freelancer','contract','internship','seasonal']);
            $table->text('job_report')->nullable();
            $table->bigInteger('price')->nullable();
            $table->text('description')->nullable();
            $table->text('qualification')->nullable();
            $table->enum('location_type',['on_site','hybrid','remote']);
            $table->string('experience_min');
            $table->string('education_min');
            $table->date('expired');
            $table->bigInteger('number_hired')->unsigned();
            $table->timestamp('job_closed')->nullable();
            $table->enum('is_ended',['yes','no'])->default('no');
            $table->bigInteger('hiring_cost')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_category')->references('id')->on('hrjob_categories')->onDelete('cascade');
            $table->foreign('id_outlet')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('id_city')->references('id')->on('cities')->onDelete('cascade');
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
