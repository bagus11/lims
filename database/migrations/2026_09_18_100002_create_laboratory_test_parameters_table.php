<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryTestParametersTable extends Migration
{
    public function up()
    {
        Schema::create('laboratory_test_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('result_type');
            $table->string('default_unit')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['result_type', 'status']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laboratory_test_parameters');
    }
}
