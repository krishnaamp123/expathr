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
        Schema::create('user_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_job');
            $table->date('interview_date')->nullable();
            $table->string('time')->nullable();
            $table->bigInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->string('location')->nullable();
            $table->string('link')->nullable();
            $table->enum('arrival',['yes','no'])->nullable();
            $table->timestamps();

            $table->foreign('id_user_job')->references('id')->on('user_hrjobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interviews');
    }
};
