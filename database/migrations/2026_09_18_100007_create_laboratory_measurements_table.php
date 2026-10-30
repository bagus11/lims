<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryMeasurementsTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_detail_id');
            $table->unsignedInteger('sequence');
            $table->decimal('value', 18, 6)->nullable();
            $table->text('text_value')->nullable();
            $table->timestamps();

            $table->unique(['laboratory_detail_id', 'sequence'], 'laboratory_measurement_sequence_unique');
            $table->foreign('laboratory_detail_id')->references('id')->on('laboratory_details')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_measurements');
    }
}
