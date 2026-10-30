<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryHeadersTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_headers', function (Blueprint $table) {
            $table->id();
            $table->string('laboratory_code')->unique();
            $table->string('report_no')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_method_id');
            $table->date('test_date');
            $table->string('production_code_number')->nullable();
            $table->string('material_lot_number')->nullable();
            $table->text('formula_snapshot')->nullable();
            $table->json('specification_snapshot')->nullable();
            $table->json('method_snapshot')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedTinyInteger('shift')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('current_approval')->default(0);
            $table->unsignedBigInteger('approval_id')->nullable();
            $table->boolean('lock')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['test_date', 'status']);
            $table->index(['location_id', 'status']);
            $table->foreign('product_id')->references('id')->on('master_product')->onDelete('restrict');
            $table->foreign('product_method_id')->references('id')->on('laboratory_product_methods')->onDelete('restrict');
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('set null');
            $table->foreign('approval_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_headers');
    }
}
