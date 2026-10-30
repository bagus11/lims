<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResalesGoodModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resales_good_models', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->integer('location_id');
            $table->integer('product_id');
            $table->integer('status');
            // $table->integer('type');
            $table->integer('current_approval');
            $table->integer('user_id');
            $table->string('approval_code');
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
        Schema::dropIfExists('resales_good_models');
    }
}
