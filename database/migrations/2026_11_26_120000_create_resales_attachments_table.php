<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResalesAttachmentsTable extends Migration
{
    /**
     * Lampiran berkas untuk modul Resales Goods (sertifikat vendor per
     * transaksi, drawing PDF per produk master).
     *
     * Isi berkas disimpan base64 di kolom tersendiri, bukan di tabel induk,
     * supaya query daftar transaksi maupun daftar master tidak pernah ikut
     * menarik berkas. Aplikasi ini tidak punya direktori upload yang persisten
     * (lihat users.signature yang juga base64), jadi menyimpannya di database
     * membuat lampiran tetap ada setelah container dibangun ulang.
     */
    public function up()
    {
        Schema::create('resales_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type', 20);   // product | transaction
            $table->string('owner_key', 100);   // id master produk, atau transaction_code
            $table->string('kind', 30);         // drawing | certificate
            $table->string('file_name');
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size')->default(0);
            $table->longText('content');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['owner_type', 'owner_key', 'kind'], 'resales_attachments_owner_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resales_attachments');
    }
}
