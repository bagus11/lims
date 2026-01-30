<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInspectorOnSubDetailInspectionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_sub_detail_logs', function (Blueprint $table) {
            $table->integer('inspector');
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
            $table->dropColumn('inspector');
        });
    }
}
