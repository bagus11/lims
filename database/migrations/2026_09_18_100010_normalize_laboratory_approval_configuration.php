<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeLaboratoryApprovalConfiguration extends Migration
{
    public function up()
    {
        $approvalCodes = DB::table('approval_headers')
            ->where('inspection_type', 1)
            ->where('type', '<>', 1)
            ->pluck('approval_code');

        if ($approvalCodes->isEmpty()) {
            return;
        }

        DB::table('approval_headers')
            ->whereIn('approval_code', $approvalCodes)
            ->update(['type' => 1]);

        DB::table('approval_details')
            ->whereIn('approval_code', $approvalCodes)
            ->update(['inspection_type' => 1]);
    }

    public function down()
    {
        $legacyApprovalCodes = DB::table('approval_headers')
            ->where('inspection_type', 1)
            ->where('approval_code', 'like', '%/2/%')
            ->pluck('approval_code');

        if ($legacyApprovalCodes->isEmpty()) {
            return;
        }

        DB::table('approval_headers')
            ->whereIn('approval_code', $legacyApprovalCodes)
            ->update(['type' => 2]);

        DB::table('approval_details')
            ->whereIn('approval_code', $legacyApprovalCodes)
            ->update(['inspection_type' => 2]);
    }
}
