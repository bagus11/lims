<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInspectionTypeApprovalHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->integer('inspection_type')->after('transaction_code');
            $table->integer('type')->after('transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropColumn('inspection_type');
            $table->dropColumn('type');
        });
    }
}
