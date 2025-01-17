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
        Schema::create('form_hrjobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_job');
            $table->unsignedBigInteger('id_form');
            $table->timestamps();

            $table->foreign('id_job')->references('id')->on('hrjobs')->onDelete('cascade');
            $table->foreign('id_form')->references('id')->on('forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_hrjobs');
    }
};
