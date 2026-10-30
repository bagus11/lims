# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

LIMS dipakai oleh tim QC/laboratorium, approver, dan administrator PT Pralon. Operator membuat transaksi pengujian, mengisi hasil ukur, dan mengirimkannya untuk approval. Administrator mengatur metode, parameter, standar, dan pemetaan pengujian per produk.

## Product Purpose

LIMS mencatat inspeksi dan pengujian mutu dari data master sampai hasil yang disetujui. Modul Product Laboratory memungkinkan setiap produk memakai metode dan parameter uji yang berbeda tanpa aturan yang di-hardcode berdasarkan nama atau seri produk.

## Positioning

Konfigurasi produk, metode, parameter, sumber standar, jumlah pengukuran, dan perhitungan menentukan formulir serta evaluasi transaksi secara dinamis. Hasil historis tetap stabil melalui snapshot konfigurasi dan standar saat transaksi dibuat.

## Operating Context

Pengguna bekerja dengan master produk, lokasi, mesin, shift, lot material, kode produksi, hasil pengukuran laboratorium, dan approval bertahap. Transaksi bergerak dari Draft ke In Progress, Approval, lalu Done atau Revision/Rejected.

## Capabilities and Constraints

- Laravel 8, Blade, Bootstrap/Argon, jQuery, DataTables, Select2, MySQL/MariaDB.
- Master produk existing di tabel `master_product` wajib dipakai tanpa mengubah kolom produksinya.
- Standar dapat berasal dari field produk yang diizinkan atau nilai statis.
- Hasil mendukung numeric, text, dan boolean; pengukuran numeric disimpan dinamis per baris.
- Perhitungan awal hanya NONE, AVG, MIN, dan MAX; operator evaluasi awal hanya `>=`, `<=`, `=`, dan BETWEEN.
- Approval memakai infrastruktur `approval_headers`, `approval_details`, dan `approval_histories` yang sudah ada.
- Lokasi berasal dari koneksi database sekunder dan tidak dapat diberi foreign key lintas database.
- UI harus mengikuti pola aplikasi existing dan tidak menambah frontend framework atau package baru.

## Brand Commitments

Nama produk adalah LIMS PT Pralon. Bahasa antarmuka operasional mengikuti campuran istilah Indonesia dan istilah teknis laboratorium yang sudah dipakai aplikasi. Logo dan identitas visual existing tetap dipertahankan.

## Evidence on Hand

- Implementasi UI dan workflow Inspection Goods, Inspection Material, Resales Goods, Approval, serta Dashboard di repository.
- Master produk produksi memuat sumber standar OD dan WT.
- Data dummy dashboard bukan bukti transaksi produksi dan tidak boleh diperlakukan sebagai data aktual.

## Product Principles

- Konfigurasi menentukan pengujian; nama produk tidak pernah menentukan logika melalui hardcode.
- Data historis tidak berubah ketika master atau konfigurasi berubah.
- Data terkunci tidak dapat diedit oleh alur transaksi biasa.
- Integrasi baru tidak mengubah perilaku modul inspeksi lama.
- Operasi multi-tabel harus atomik dan hasil evaluasi dapat ditelusuri ke pengukuran mentahnya.
