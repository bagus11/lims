<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLhuSampleFieldsToLaboratoryHeaders extends Migration
{
    /**
     * Bagian "Keterangan Contoh" pada LHU (Laporan Pengujian Pipa PVC)
     * membutuhkan tiga keterangan yang tidak bisa diturunkan dari data lain.
     * Opsional; report memberi strip saat kosong.
     */
    public function up()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->string('sample_name')->nullable()->after('made_for');
            $table->string('sample_condition')->nullable()->after('sample_name');
            $table->string('sample_instruction')->nullable()->after('sample_condition');
        });
    }

    public function down()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->dropColumn(['sample_name', 'sample_condition', 'sample_instruction']);
        });
    }
}
