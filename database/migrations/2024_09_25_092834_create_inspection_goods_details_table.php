<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionGoodsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_goods_details', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->string('detail_inspection_code');
            $table->integer('shift');
            $table->integer('status');
            $table->integer('user_id');
            $table->text('evaluation');
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
        Schema::dropIfExists('inspection_goods_details');
    }
}
