<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResalesGoodsTable extends Migration
{
    public function up()
    {
        Schema::create('resales_goods', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->string('standard')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

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
        Schema::dropIfExists('resales_goods');
    }
}
