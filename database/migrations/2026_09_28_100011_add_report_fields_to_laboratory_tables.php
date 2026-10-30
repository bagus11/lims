<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportFieldsToLaboratoryTables extends Migration
{
    /**
     * Fields that only exist on the printed "Record of Laboratory Testing"
     * form (03/QC/12-1999 Rev-00) and had no home in the schema yet.
     */
    public function up()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->string('temperature')->nullable()->after('shift');
            $table->string('humidity')->nullable()->after('temperature');
            $table->string('product_type')->nullable()->after('humidity');
            $table->text('comment')->nullable()->after('product_type');
        });

        Schema::table('laboratory_details', function (Blueprint $table) {
            $table->text('description_snapshot')->nullable()->after('parameter_name_snapshot');
        });
    }

    public function down()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->dropColumn(['temperature', 'humidity', 'product_type', 'comment']);
        });

        Schema::table('laboratory_details', function (Blueprint $table) {
            $table->dropColumn('description_snapshot');
        });
    }
}
