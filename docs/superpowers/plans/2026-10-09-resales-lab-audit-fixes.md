# Resales Goods & Laboratory Audit Fixes — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Perbaiki semua temuan CRITICAL + HIGH (gelombang 1–3 laporan audit 9 Okt 2026) di modul inspection Resales Goods & Laboratory, plus quick-win MEDIUM yang menempel pada perbaikan yang sama.

**Architecture:** Laravel 9 monolith, jQuery/DataTables frontend di `public/lims`, approval berbasis `ApprovalHeader/ApprovalDetail` (setting) + `ApprovalHistory` (progress). Pola sehat yang sudah ada di modul Laboratory (FormRequest, DB::transaction + lockForUpdate, ValidationException) dipakai sebagai template untuk Resales.

**Tech Stack:** PHP 8 / Laravel 9, MySQL (dua koneksi: default + `mysql2` work_order), Spatie Permission, Maatwebsite Excel, mPDF, jQuery + DataTables.

## Global Constraints

- Tes dijalankan terhadap DB MySQL lokal (pola `DatabaseTransactions` + `markTestSkipped` bila data master tak ada) — lihat `tests/Feature/LaboratoryWorkflowTest.php`.
- MySQL lokal dijalankan manual via `mysql.server start` (JANGAN brew services).
- `signature` user dipakai server-side untuk PDF — boleh di-`$hidden` (serialization only), TIDAK boleh dihapus dari query server-side report.
- Jangan ubah perilaku modul Goods/Material (type 2 & 3) selain menghapus `dd()`.
- Status Resales: 0 Draft, 1 In Progress, 2 Approval Progress, 3 Done. Revision mengembalikan ke 1 (tanpa status baru).
- Status Lab: 0 Draft, 1 In Progress, 2 Waiting Approval, 3 Done, 4 Revision.
- Semua endpoint JSON; error user-facing lewat ValidationException / ResponseFormatter::error.

---

### Task 1 — Wave 1: hapus dd(), route delete POST, sheet title, sembunyikan signature/nik

**Files:**
- Modify: `app/Http/Controllers/Setting/ApprovalController.php:178-180,259`
- Modify: `routes/web.php:116`
- Modify: `public/lims/master/master_product_resales_goods/master_product_resales_goods.js` (tidak berubah — sudah POST)
- Modify: `app/Exports/Transaction/Inspection/ResalesGoodsExport.php:63`
- Modify: `app/Exports/Transaction/Inspection/LaboratoryReportExport.php` (cek title() juga)
- Modify: `app/Models/User.php` ($hidden += signature, nik)
- Modify: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php:33` (`User::all()` → kolom minimal)
- Modify: `app/Http/Controllers/Transaction/Inspection/LaboratoryController.php:49` (users → kolom minimal)

- [ ] `dd('test')` (baris 179): ganti dengan ValidationException "Approval step configuration is invalid for this transaction."
- [ ] `dd($countApproval)` (baris 259): hapus baris.
- [ ] Route `deleteMasterProductResalesGoods` GET → POST.
- [ ] `ResalesGoodsExport::title()`: `str_replace(['/', '\\', '?', '*', '[', ']', ':'], '-', ...)` (karakter ilegal PhpSpreadsheet), max 31 char.
- [ ] `User::$hidden` += `signature`, `nik`. Grep JS untuk pemakaian `.nik`/`.signature` di frontend; jika ada pemakaian sah, sesuaikan (ekspektasi: tidak ada).
- [ ] `getOptionLists` (Resales) & `getLaboratoryOptions`: `User::select('id','name')->orderBy('name')->get()`.
- [ ] Verify: `php -l` semua file PHP berubah; `php artisan route:list | grep -i deleteMasterProduct` menunjukkan POST.

### Task 2 — Wave 1/2: guard cascade delete master product resales (H6, pasangan C2)

**Files:**
- Modify: `app/Http/Controllers/Master/MasterProductResalesGoodController.php` (deleteMasterProductResalesGoods)
- Create: `database/migrations/2026_10_09_100001_restrict_resales_goods_product_fk.php`

- [ ] Controller: sebelum delete, `ResalesGood::where('product_id',$id)->exists()` → error 400 "Master product is used by N transaction(s) and cannot be deleted."
- [ ] Migration: drop FK `resales_goods.product_id` & `resales_goods_detail.product_id` yang cascade, re-add `onDelete('restrict')`. (`resales_goods_detail.transaction_code` cascade ke header DIBIARKAN — itu memang hirarki parent-child.)
- [ ] Verify: `php artisan migrate` lokal sukses.

### Task 3 — Wave 2: handler approval Resales Goods (C1) — backend

**Files:**
- Create: `app/Http/Requests/Setting/Approval/UpdateResalesGoodsApprovalRequest.php` (mirror UpdateLaboratoryApprovalRequest: `transaction_code required|exists:resales_goods,transaction_code`, `decision Rule::in([1,4])`, `remark required|string`)
- Modify: `app/Http/Controllers/Setting/ApprovalController.php` (+method `updateResalesGoodsApproval`)
- Modify: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php` (+method `getResalesGoodApprovalDetail`)
- Modify: `routes/web.php` (POST `/updateResalesGoodsApproval` di blok Approval; GET `/getResalesGoodApprovalDetail` di blok Resales)

- [ ] `updateResalesGoodsApproval` meniru `updateLaboratooryApproval`: DB::transaction; lockForUpdate header (status harus 2); history aktif = ApprovalHistory(transaction_code, inspection_type 4, user auth, status 0) terbaru — kalau tidak ada → "You are not the active approver". Update history {status: decision, remark}. Decision 4 → header {status 1, current_approval 0}. Decision 1 → cari ApprovalDetail step+1 pada ApprovalHeader(inspection_type 4, type 1, location header); ada → create history step+1 + header current_approval=step+1; tidak ada → header {status 3 (Done), current_approval step}.
- [ ] `getResalesGoodApprovalDetail`: header (with relations) + details + history(inspection_type 4) by transaction_code; akses: assigned approver (ada history user_id=auth) ATAU lokasi sama ATAU GM.
- [ ] Verify: `php artisan route:list | grep -iE 'updateResalesGoodsApproval|getResalesGoodApprovalDetail'`.

### Task 4 — Wave 2: dashboard approval Resales (C1) — frontend

**Files:**
- Create: `resources/views/dashboard/modal/approval/resales/detail-resales.blade.php` (mengikuti pola `dashboard/modal/approval/laboratory/detail-laboratory.blade.php`)
- Modify: `resources/views/dashboard.blade.php` (include modal baru, baris ~604)
- Modify: `public/lims/dashboard/dashboard.js:289-321` (ganti branch type-4-pakai-Pellet dengan modal Resales sendiri)

- [ ] Branch `inspection_type == '4'` → `getCallback('getResalesGoodApprovalDetail', {transaction_code}, ...)` → render summary header + tabel detail spesifikasi + riwayat approval → modal `#dashboardResalesApprovalModal`.
- [ ] Tombol submit → POST `updateResalesGoodsApproval` {transaction_code, decision, remark}; sukses → refresh `getApprovalUser`.
- [ ] Semua nilai dirender lewat escaper (Task 8 helper).
- [ ] Hapus `console.log` sisa debug di mappingApproval.

### Task 5 — Wave 2: guard status & delete Resales (C5, H1)

**Files:**
- Modify: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php` (addResalesGoodsDetail, editResalesGoodsDetail, deleteResalesGoodsDetail, deleteResalesGoods)

- [ ] add/edit/delete detail: tolak bila header.status >= 2 ("transaction is locked").
- [ ] deleteResalesGoods: hanya status 0/1; hapus juga `ApprovalHistory::where('transaction_code',...)` (sisa siklus revisi) di dalam transaksi.
- [ ] deleteResalesGoodsDetail: reset status→0 hanya bila status sekarang 1.

### Task 6 — Wave 2: alur Revision Lab (H1 lab) + dedup penandatangan report (H2 lab)

**Files:**
- Modify: `app/Http/Controllers/Transaction/Inspection/LaboratoryController.php` (lockProductLaboratory: terima status 1 ATAU 4; buildLaboratoryReport: history = siklus terakhir)
- Modify: `public/lims/transaction/inspection/laboratory.js` (~185: tombol submit tampil untuk status 4 juga)

- [ ] lockProductLaboratory: `in_array($header->status,[1,4])`.
- [ ] buildLaboratoryReport: potong history ke entri SETELAH history status=4 terakhir; lalu `groupBy('step')->map(last)` urut step — slot tanda tangan tidak dobel.
- [ ] laboratory.js: cek render tombol submit/lock → tambah kondisi status 4.

### Task 7 — Wave 2: generator kode transaksi aman-race (H2 resales + MEDIUM lab)

**Files:**
- Modify: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php` (addResalesGoodsHeader)

- [ ] Pindahkan generate+create ke dalam satu `DB::transaction`; ambil last row `ResalesGood::orderBy('id','desc')->lockForUpdate()->first()`; parsing `explode('/')` diguard `count($parts)>=4 && is_numeric($parts[0])`, fallback sequence 1.

### Task 8 — Wave 3: escaper bersama + XSS fixes (C4 resales, H4 lab)

**Files:**
- Modify: `public/lims/helper.js` (+`window.escapeHtml` — escape & < > " ')
- Modify: `public/lims/transaction/inspection/resales_goods.js` (semua titik injeksi: keterangan, no_pf, brand, packaging, certificate/standard, nama relasi, spec symbol/value, atribut data-*)
- Modify: `public/lims/transaction/inspection/laboratory.js`, `public/lims/master/laboratory/laboratory.js`, `public/lims/dashboard/dashboard.js` (ganti escaper `$('<div>').text(v).html()` → escapeHtml)
- Modify: `resources/views/report/inspection/report_resales_goods.blade.php` ({!! !!} → `e()` untuk data user)

- [ ] `window.escapeHtml = function(v){ if(v===null||v===undefined) return ''; return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;') }`
- [ ] Audit tiap concat HTML di resales_goods.js; nilai user-controlled dibungkus escapeHtml (badge, infoTr value, title attr, data-sym-static, value="").
- [ ] report_resales_goods blade: ganti `{!! $x !!}` menjadi `{{ }}` / `e()` kecuali HTML yang memang dibangun server dari konstanta.

### Task 9 — Wave 3: IDOR & scoping lokasi (C3 resales, H3 lab) + hasPermissionTo aman (H5)

**Files:**
- Modify: `app/Models/User.php` (+method `hasPermissionSafe(string $name): bool` — catch `PermissionDoesNotExist` → false)
- Modify: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php` (helper `authorizeHeaderAccess($header)` dipakai di getResalesGoodDetails, add/edit/delete detail, lock, delete, print, export, approval-detail)
- Modify: `app/Http/Controllers/Transaction/Inspection/LaboratoryController.php` (guard di getProductLaboratoryDetail, saveLaboratoryMeasurement, lock, comment, print, export; listing: param `location_id` hanya dihormati untuk GM)

- [ ] Aturan akses: GM (`hasPermissionSafe('get-only_gm-inspection-goods')`) → semua; selain itu `header.location_id == auth()->user()->kode_kantor`; khusus endpoint approval-detail & print/export: approver yang ditugaskan (punya ApprovalHistory di transaksi itu) juga boleh.
- [ ] Create (resales header & lab header): non-GM dipaksa `location_id = kode_kantor`.
- [ ] Ganti semua call `hasPermissionTo` di kedua controller → `hasPermissionSafe`.

### Task 10 — Wave 3: otorisasi route setting approval & master (C2 lab)

**Files:**
- Modify: `app/Http/Controllers/Setting/ApprovalController.php` (updateApproval: guard permission + fix undefined `$post` bila user_array kosong)
- Modify: FormRequest `authorize()` untuk master Laboratory & master Resales & approval setting → cek permission Spatie bila ada, fallback deny untuk non-admin? — KEPUTUSAN: pakai `hasPermissionSafe('setting-approval')` dkk TIDAK bisa karena row permission produksi tidak diketahui. Pendekatan aman: batasi mutasi master & setting approval ke user dengan role admin ATAU permission eksplisit bila ada (`hasAnyRole` guard-insensitive, fallback permission). Implement helper `User::canManageMasters()`:
  `return $this->hasPermissionSafe('manage-masters') || $this->hasPermissionSafe('setting-approval') || $this->roles()->pluck('name')->contains(fn($r) => stripos($r,'admin') !== false);`
- [ ] Terapkan di authorize() FormRequest: UpdateApprovalRequest, StoreApprovalHeaderRequest, UpdateLaboratoryApprovalRequest? (TIDAK — itu transaksi approval, bukan setting), semua FormRequest Master/Laboratory/* dan Master/ResalesGoods/*, + controller master yang pakai Request polos (deleteMasterProductResalesGoods).
- [ ] updateApproval: `$post` → `$array_post ? end($array_post) : null`; tolak `user_array` kosong lewat validasi.

### Task 11 — Wave 3/4 quick wins: error feedback + validasi + tombol print Done

**Files:**
- Modify: `public/lims/helper.js` (fallback: error 422 tanpa span target → toastr pesan pertama)
- Modify: `app/Http/Requests/Transaction/Inspection/ResalesGoods/StoreResalesGoodHeaderRequest.php` (exists: + integer + min:1 quantity)
- Modify: `public/lims/transaction/inspection/resales_goods.js` (tombol print tampil status >= 2)
- Modify: `app/Http/Controllers/Transaction/Inspection/LaboratoryController.php` (saveLaboratoryComment: validasi `comment` string|nullable|max)

### Task 12 — Tes & verifikasi

**Files:**
- Create: `tests/Feature/ResalesGoodsWorkflowTest.php` (pola LaboratoryWorkflowTest: DatabaseTransactions, skip bila master/approval setting type 4 tidak ada; alur create → add detail → lock → approve semua step → Done; revision → status 1 → edit boleh lagi; guard: edit detail saat locked = 422; delete non-draft = 422; user lokasi lain = 403/422)

- [ ] `php artisan test` hijau (termasuk LaboratoryWorkflowTest & LaboratoryRuleEngineTest tidak regresi).
- [ ] `php -l` semua PHP berubah, `node --check` semua JS berubah.
- [ ] `php artisan route:list` memuat route baru.
- [ ] Smoke manual via `php artisan serve --port=8787` + Playwright bila perlu.
- [ ] Commit bertahap per task (pesan `fix(resales|lab): ...`).
