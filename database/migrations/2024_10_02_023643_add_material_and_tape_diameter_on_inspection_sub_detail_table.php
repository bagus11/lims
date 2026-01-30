<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialAndTapeDiameterOnInspectionSubDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_goods_sub_details', function (Blueprint $table) {
            $table->integer('material_id')->after('shift');
            $table->float('tape_diameter')->after('shift');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspection_goods_sub_details', function (Blueprint $table) {
            $table->dropColumn('material_id');
            $table->dropColumn('tape_diameter');
        });
    }
}
