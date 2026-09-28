<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('resales_goods', 'no_pf')) {
            Schema::table('resales_goods', function (Blueprint $table) {
                $table->string('no_pf')->nullable()->after('packaging');
            });
        }
        if (!Schema::hasColumn('resales_goods', 'keterangan')) {
            Schema::table('resales_goods', function (Blueprint $table) {
                $table->text('keterangan')->nullable()->after('no_pf');
            });
        }
        if (!Schema::hasColumn('master_product_resales_goods', 'uom')) {
            Schema::table('master_product_resales_goods', function (Blueprint $table) {
                $table->string('uom')->nullable()->after('size');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('resales_goods', 'no_pf')) {
            Schema::table('resales_goods', function (Blueprint $table) {
                $table->dropColumn('no_pf');
            });
        }
        if (Schema::hasColumn('resales_goods', 'keterangan')) {
            Schema::table('resales_goods', function (Blueprint $table) {
                $table->dropColumn('keterangan');
            });
        }
        if (Schema::hasColumn('master_product_resales_goods', 'uom')) {
            Schema::table('master_product_resales_goods', function (Blueprint $table) {
                $table->dropColumn('uom');
            });
        }
    }
};
