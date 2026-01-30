<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionGoodsSubDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_goods_sub_details', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_header');
            $table->string('inspection_detail');
            $table->string('inspection_sub_detail');
            $table->time('time');
            $table->string('shift');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('length_of_pipe');
            $table->integer('appearance_of_pipe');
            $table->integer('marking');
            $table->integer('condition_pipe_of_cut');
            $table->integer('angle_pipe_of_spigot');
            $table->integer('color_of_pipes');
            $table->integer('evaluation_spec');
            $table->integer('status');
            $table->longText('remark');
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
        Schema::dropIfExists('inspection_goods_sub_details');
    }
}
