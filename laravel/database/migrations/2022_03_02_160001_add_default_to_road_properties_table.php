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
        Schema::table('road_properties', function (Blueprint $table) {
            $table->string('label',255)->nullable(true)->default('-')->change();
            $table->string('reason',255)->nullable(true)->default('-')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('road_properties', function (Blueprint $table) {
            $table->string('label',255)->nullable(false)->change();
            $table->string('reason',255)->nullable(false)->change();
        });
    }
};
