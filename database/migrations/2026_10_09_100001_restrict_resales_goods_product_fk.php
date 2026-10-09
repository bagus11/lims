<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * FK product_id semula onDelete('cascade'): menghapus 1 master product ikut
 * menghapus permanen seluruh transaksi + detail historis produk tersebut.
 * Diganti restrict; guard aplikasi ada di MasterProductResalesGoodController.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('resales_goods', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')
                ->on('master_product_resales_goods')
                ->onDelete('restrict');
        });

        Schema::table('resales_goods_detail', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')
                ->on('master_product_resales_goods')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('resales_goods', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')
                ->on('master_product_resales_goods')
                ->onDelete('cascade');
        });

        Schema::table('resales_goods_detail', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')
                ->on('master_product_resales_goods')
                ->onDelete('cascade');
        });
    }
};
