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
        Schema::create('user_interviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_interview');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user_interview')->references('id')->on('user_interviews')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['id_user_interview', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interviewers');
    }
};
