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
        Schema::create('incident_bounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ip_id')->constrained('incident_properties');
            $table->string('key',10)->nullable(false);
            $table->point('lat_lon')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incident_bounds');
    }
};
