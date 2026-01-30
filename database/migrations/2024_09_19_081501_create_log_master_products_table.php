<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogMasterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_master_product', function (Blueprint $table) {
            $table->id();
            $table->string('seri');
            $table->string('join');
            $table->string('ukuran');
            $table->string('marking');
            $table->string('berat_pcs');
            $table->string('berat');
            $table->string('kode_ts');
            $table->string('kode_be');
            $table->string('marking_2');
            $table->string('formula');
            $table->string('min_od');
            $table->string('toleransi_od');
            $table->string('marking_od');
            $table->string('max_od');
            $table->string('avg_od');
            $table->string('min_wt');
            $table->string('toleransi_wt');
            $table->string('marking_wt');
            $table->string('max_wt');
            $table->string('avg_wt');
            $table->integer('user_id');
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
        Schema::dropIfExists('log_master_product');
    }
}
