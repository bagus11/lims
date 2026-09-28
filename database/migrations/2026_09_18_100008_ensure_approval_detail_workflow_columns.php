<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureApprovalDetailWorkflowColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('approval_details', 'inspection_type')) {
            Schema::table('approval_details', function (Blueprint $table) {
                $table->integer('inspection_type')->default(0)->after('user_id');
            });
        }

        if (!Schema::hasColumn('approval_details', 'step')) {
            Schema::table('approval_details', function (Blueprint $table) {
                $table->integer('step')->default(1)->after('inspection_type');
            });
        }
    }

    public function down()
    {
        // Existing deployments may already own these columns, so rollback is intentionally non-destructive.
    }
}
