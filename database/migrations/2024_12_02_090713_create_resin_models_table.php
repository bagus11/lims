<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResinModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resin_models', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->string('color');
            $table->string('visible_properties');
            $table->string('packaging');
            $table->string('particle_size');
            $table->integer('step');
            $table->float('volatile_matter');
            $table->float('amount');
            $table->float('weight');
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
        Schema::dropIfExists('resin_models');
    }
}
