<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_materials', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->integer('location');
            $table->integer('user_id');
            $table->integer('status');
            $table->integer('inspector');
            $table->integer('type');
            $table->integer('product_id');
            $table->integer('supplier');
            $table->string('merk');
            $table->float('amount');
            $table->float('weight');
            $table->string('standard');
            $table->float('package');
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
        Schema::dropIfExists('inspection_materials');
    }
}
