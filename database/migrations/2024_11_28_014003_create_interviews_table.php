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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_job');
            $table->unsignedBigInteger('id_user');
            $table->date('interview_date');
            $table->string('time');
            $table->bigInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('id_user_job')->references('id')->on('user_hrjobs');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
