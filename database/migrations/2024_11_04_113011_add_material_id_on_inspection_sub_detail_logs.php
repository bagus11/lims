<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialIdOnInspectionSubDetailLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_sub_detail_logs', function (Blueprint $table) {
            $table->integer('material_id');
            $table->float('tape_diameter');
            $table->integer('machine_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspection_sub_detail_logs', function (Blueprint $table) {
            $table->dropColumn('material_id');
            $table->dropColumn('tape_diameter');
            $table->dropColumn('machine_id');
        });
    }
}
