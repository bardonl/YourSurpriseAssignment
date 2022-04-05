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
        Schema::table('roads', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('end');
        });

        Schema::table('road_properties', function (Blueprint $table) {
            $table->string('start',255)->nullable(false);
            $table->string('end',255)->nullable(false);

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
            $table->dropColumn('start');
            $table->dropColumn('end');
        });

        Schema::table('roads', function (Blueprint $table) {
            $table->string('start',255)->nullable(false);
            $table->string('end',255)->nullable(false);

        });
    }
};
