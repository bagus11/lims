<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryProductMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_product_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('test_method_id');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'test_method_id'], 'laboratory_product_method_unique');
            $table->foreign('product_id')->references('id')->on('master_product')->onDelete('restrict');
            $table->foreign('test_method_id')->references('id')->on('laboratory_test_methods')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_product_methods');
    }
}
