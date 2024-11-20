<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('work_locations', function (Blueprint $table) {
            $table->unique(['id_user', 'id_city'], 'user_city_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('work_locations', function (Blueprint $table) {
            $table->dropUnique('user_city_unique');
        });
    }
};
