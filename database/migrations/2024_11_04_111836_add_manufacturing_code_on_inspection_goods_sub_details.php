<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManufacturingCodeOnInspectionGoodsSubDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_goods_sub_details', function (Blueprint $table) {
            $table->string('manufacturing_code');
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
            $table->dropColumn('manufacturing_code');
        });
    }
}
