<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoPofAndRemark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_materials', function (Blueprint $table) {
            $table->string('no_pof')->after('package');
            $table->longText('remark')->after('package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspection_materials', function (Blueprint $table) {
            $table->dropColumn('no_pof');
            $table->dropColumn('remark');
        });
    }
}
