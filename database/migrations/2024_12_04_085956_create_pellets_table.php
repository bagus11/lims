<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pellets', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_code');
            $table->string('visible_properties');
            $table->string('packaging');
            $table->float('amount_per_sak');
            $table->float('weight_per_sak');
            $table->float('mfr');
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
        Schema::dropIfExists('pellets');
    }
}
