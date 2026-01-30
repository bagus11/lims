<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepOnWallThicknessLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wall_thickness_logs', function (Blueprint $table) {
            $table->integer('step')->after('sub_detail_inspection_code')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wall_thickness_logs', function (Blueprint $table) {
            $table->dropColumn('step');
        });
    }
}
