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
        Schema::create('road_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('road_id')->constrained('roads');
            $table->integer('segment_id')->default(0);
            $table->integer('code_direction')->default(0);
            $table->integer('afrc')->default(0);
            $table->string('category',255)->nullable(false);
            $table->string('label',255)->nullable(false);
            $table->string('incident_type',255)->nullable(false);
            $table->string('from',255)->nullable(false);
            $table->string('to',255)->nullable(false);
            $table->string('reason',255)->nullable(false);
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
        Schema::dropIfExists('road_properties');
    }
};
