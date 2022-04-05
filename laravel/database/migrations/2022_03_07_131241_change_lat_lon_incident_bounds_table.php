<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_bounds', function (Blueprint $table) {
            $table->dropColumn('lat_lon');
            $table->point('lon_lat')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incident_bounds', function (Blueprint $table) {
            $table->dropColumn('lon_lat');
            $table->point('lat_lon')->nullable(false);
        });
    }
};
