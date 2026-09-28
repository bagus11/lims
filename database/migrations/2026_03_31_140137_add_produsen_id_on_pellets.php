<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProdusenIdOnPellets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pellets', function (Blueprint $table) {
            $table->integer('produsen_id')->after('inspection_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pellets', function (Blueprint $table) {
            $table->dropColumn('produsen_id');
        });
    }
}
