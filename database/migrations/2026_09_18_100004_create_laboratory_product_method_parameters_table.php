<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryProductMethodParametersTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_product_method_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_method_id');
            $table->unsignedBigInteger('test_parameter_id');
            $table->unsignedInteger('sequence')->default(1);
            $table->string('source_type')->nullable();
            $table->string('source_min_field')->nullable();
            $table->string('source_max_field')->nullable();
            $table->string('source_target_field')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('min_value', 18, 6)->nullable();
            $table->decimal('max_value', 18, 6)->nullable();
            $table->decimal('target_value', 18, 6)->nullable();
            $table->decimal('tolerance_plus', 18, 6)->nullable();
            $table->decimal('tolerance_minus', 18, 6)->nullable();
            $table->text('expected_value')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedInteger('measurement_count')->nullable();
            $table->string('calculation_type')->default('NONE');
            $table->boolean('is_required')->default(1);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->unique(['product_method_id', 'test_parameter_id'], 'laboratory_product_parameter_unique');
            $table->index(['product_method_id', 'sequence'], 'laboratory_product_parameter_sequence');
            $table->foreign('product_method_id')->references('id')->on('laboratory_product_methods')->onDelete('cascade');
            $table->foreign('test_parameter_id')->references('id')->on('laboratory_test_parameters')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_product_method_parameters');
    }
}
