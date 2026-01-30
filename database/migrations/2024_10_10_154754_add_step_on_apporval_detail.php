<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepOnApporvalDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_details', function (Blueprint $table) {
            // $table->integer('step')->after('user_id');
            // $table->integer('inspection_type')->after('user_id');
            $table->dropColumn('detail_approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_details', function (Blueprint $table) {
            // $table->dropColumn('step');
            // $table->dropColumn('inspection_type');
            $table->string('detail_approval')->after('approval_code');
        });
    }
}
