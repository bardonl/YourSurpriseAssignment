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
        Schema::create('incident_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rp_id')->constrained('road_properties');
            $table->text('polyline')->nullable(true);
            $table->dateTime('start')->nullable(true);
            $table->dateTime('end')->nullable(true);
            $table->string('hm',10)->nullable(true)->default(0);
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
        Schema::dropIfExists('incident_properties');
    }
};
