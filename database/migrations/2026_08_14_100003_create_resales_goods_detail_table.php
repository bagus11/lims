<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResalesGoodsDetailTable extends Migration
{
    public function up()
    {
        Schema::create('resales_goods_detail', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code');
            $table->unsignedBigInteger('product_id');
            $table->json('spesification')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('transaction_code')
                ->references('transaction_code')
                ->on('resales_goods')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('master_product_resales_goods')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resales_goods_detail');
    }
}
