<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToResalesGoodsTable extends Migration
{
    public function up()
    {
        Schema::table('resales_goods', function (Blueprint $table) {
            $cols = [
                'status'            => fn () => $table->integer('status')->default(0),
                'current_approval'  => fn () => $table->integer('current_approval')->default(0),
                'location_id'       => fn () => $table->unsignedBigInteger('location_id')->nullable(),
                'supplier_id'       => fn () => $table->unsignedBigInteger('supplier_id')->nullable(),
                'produsen_id'       => fn () => $table->unsignedBigInteger('produsen_id')->nullable(),
                'certificate'       => fn () => $table->string('certificate')->nullable(),
                'brand'             => fn () => $table->string('brand')->nullable(),
                'packaging'         => fn () => $table->string('packaging')->nullable(),
                'type'              => fn () => $table->string('type')->nullable()->comment('fitting / non fitting'),
                'inspector_id'      => fn () => $table->unsignedBigInteger('inspector_id')->nullable(),
            ];

            foreach ($cols as $col => $builder) {
                if (!Schema::hasColumn('resales_goods', $col)) {
                    $builder();
                }
            }
        });
    }

    public function down()
    {
        Schema::table('resales_goods', function (Blueprint $table) {
            $cols = [
                'status',
                'current_approval',
                'location_id',
                'supplier_id',
                'produsen_id',
                'certificate',
                'brand',
                'packaging',
                'type',
                'inspector_id',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('resales_goods', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
