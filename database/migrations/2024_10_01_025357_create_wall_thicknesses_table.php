<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallThicknessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_thicknesses', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->string('detail_inspection_code');
            $table->string('sub_detail_inspection_code');
            $table->float('wt_1');
            $table->float('wt_2');
            $table->float('wt_3');
            $table->float('wt_4');
            $table->float('wt_5');
            $table->float('wt_6');
            $table->float('wt_7');
            $table->float('wt_8');
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
        Schema::dropIfExists('wall_thicknesses');
    }
}
