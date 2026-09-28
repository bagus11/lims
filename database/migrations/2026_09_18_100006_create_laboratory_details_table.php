<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_header_id');
            $table->unsignedBigInteger('product_method_parameter_id')->nullable();
            $table->unsignedInteger('sequence');
            $table->string('parameter_name_snapshot');
            $table->string('result_type_snapshot');
            $table->json('standard_snapshot')->nullable();
            $table->string('unit_snapshot')->nullable();
            $table->string('calculation_type_snapshot')->default('NONE');
            $table->unsignedInteger('measurement_count_snapshot')->nullable();
            $table->boolean('is_required_snapshot')->default(1);
            $table->decimal('result_value', 18, 6)->nullable();
            $table->text('text_result')->nullable();
            $table->boolean('boolean_result')->nullable();
            $table->string('evaluation')->nullable();
            $table->unsignedBigInteger('in_charge_user_id')->nullable();
            $table->unsignedBigInteger('checker_user_id')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->unique(['laboratory_header_id', 'sequence'], 'laboratory_detail_sequence_unique');
            $table->foreign('laboratory_header_id')->references('id')->on('laboratory_headers')->onDelete('cascade');
            $table->foreign('product_method_parameter_id')->references('id')->on('laboratory_product_method_parameters')->onDelete('set null');
            $table->foreign('in_charge_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('checker_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_details');
    }
}
