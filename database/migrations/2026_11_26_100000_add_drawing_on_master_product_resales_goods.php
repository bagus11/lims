<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDrawingOnMasterProductResalesGoods extends Migration
{
    /**
     * Gambar teknik produk, disimpan sebagai data URI base64 mengikuti pola
     * users.signature. Report dicetak mPDF yang membaca data URI langsung,
     * jadi tidak perlu direktori upload yang bisa hilang saat deploy ulang.
     */
    public function up()
    {
        Schema::table('master_product_resales_goods', function (Blueprint $table) {
            $table->longText('drawing')->nullable()->after('uom');
        });
    }

    public function down()
    {
        Schema::table('master_product_resales_goods', function (Blueprint $table) {
            $table->dropColumn('drawing');
        });
    }
}
