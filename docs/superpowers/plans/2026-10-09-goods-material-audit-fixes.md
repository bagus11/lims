# Goods & Material Audit Fixes — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Perbaiki semua temuan CRITICAL + HIGH audit modul Inspection Goods (type 3) & Inspection Material resin/pellet (type 2) — laporan https://claude.ai/code/artifact/8324763e-29c0-4ebd-adc2-a00e0849c0f1.

**Architecture:** Tulis ulang inti approval type 2/3 mengikuti pola sehat yang sudah terbukti di `updateLaboratoryApproval`/`updateResalesGoodsApproval` (lockForUpdate, approver aktif = history status 0 milik user, approve → step berikut/selesai, reject → status Rejected + unlock). Endpoint & nama field request TIDAK berubah (dashboard.js tetap kompatibel). Helper keamanan yang sudah ada dipakai ulang: `hasPermissionSafe`, `canManageModule`, `window.escapeHtml`, pola `canAccessLocation/canViewTransaction`.

**Tech Stack:** Laravel 8, MySQL/MariaDB lokal (copy produksi), jQuery/DataTables, mPDF, Spatie Permission.

## Global Constraints

- Endpoint `updateInspectionGoodsApproval` + field request lama dipertahankan (approval_insepection_goods_shift_id/ig_remark; approval_resin/imr_remark/transaction_code; approval_pellet/imr_pellet_remark/label_pellet_inspection_code). Decision: **1=Approve, 2=Reject**.
- Status Material: 0 Draft, 1 In Progress, 2 Approval Progress (lock=1), 3 Done, 4 Rejected (lock=0, bisa diedit & lock ulang).
- Status InspectionGoodsDetail: 0 Incomplete (lock=0), 1 Approval Progress (lock=1), 2 Complete. Reject → kembali 0 + lock=0.
- Status header InspectionGood: 0 NEW, 1 In Progress, 2 Approval Progress, 3 DONE (ketika 3 detail status 2).
- Tes pola `DatabaseTransactions` + `markTestSkipped` (lihat ResalesGoodsWorkflowTest); MySQL manual, JANGAN brew services.
- Error user-facing via ValidationException (422) / ResponseFormatter::error; jangan 500.
- Modul Resales/Lab tidak boleh regresi — `php artisan test` penuh tiap commit besar.

---

### Task 1 — Tulis ulang approval type 2 & 3 (G-C1, G-C2, M-C1, 0.1, 0.2)

**Files:** Modify `app/Http/Controllers/Setting/ApprovalController.php` (ganti seluruh isi `updateInspectionGoodsApproval` dengan dispatcher → `approveInspectionMaterial()` dan `approveInspectionGoodsShift()` private), Modify `app/Http/Requests/Setting/Approval/UpdateInspectionGoodsApprovalRequest.php` (decision Rule::in([1,2]) per cabang, authorize auth()->check()).

- [ ] Material: lockForUpdate header by code (resin: transaction_code; pellet: label_pellet_inspection_code ?? transaction_code); wajib status==2 && lock==1; history aktif (type 2, user, status 0) terbaru wajib ada ("You are not the active approver"); update history {status: decision, remark}; decision 2 → header {status 4, lock 0, approval_id 0}; decision 1 → next step dari ApprovalHeader(inspection_type 2, shift 1, location) → ada: history baru + approval_id; habis: {status 3}.
- [ ] Goods: lockForUpdate InspectionGoodsDetail by detail_inspection_code; wajib lock==1 && status==1; history aktif type 3; decision 2 → detail {status 0, lock 0, approval_id 0} + subdetail status 0 + header InspectionGood status = ada detail lain lock=1 ? 2 : 1; decision 1 → next step dari ApprovalHeader(inspection_type 3, shift detail, location header) → ada: history + approval_id; habis: detail {status 2, approval_id 0}; lalu bila 3 detail inspection_code itu status 2 semua → header {status 3} (pakai `$detail->inspection_code`, BUKAN `$approvalHeader->inspection_code`).
- [ ] Verify: `php -l`, `php artisan test` (regresi lab/resales hijau).

### Task 2 — Guard lock + null guard (G-H1, M-C4, M-H2/G-H5-part)

**Files:** Modify `GoodsController::lockInspectionGoods`, `InspectionMaterialController::lockInspectionMaterial`.

- [ ] Material: validasi request inspection_code; tolak bila header null (404), lock==1 atau status ∉ [1,4] (400); approval setting / step 1 null → 400 jelas; set status **2** eksplisit (bukan +1), lock 1.
- [ ] Goods: tolak bila detail null, lock==1, status != 0 (400), subdetail kosong (400); approval setting/step null → 400; set detail {lock 1, status 1}, subdetail status 1, header status 2 bila <2; history step 1.

### Task 3 — Guard mutasi pasca-lock (G-C3, M-C3) + JS status 4

**Files:** Modify `GoodsController` (updateInspectionGoodSubDetail, updateWallThickness, addWallThickness), `InspectionMaterialController` (updateResinDetail, updateResinProperties, updatePelletProperties), `public/lims/transaction/inspection/inspection_material.js` (btn_edit & btn_lock tampil juga untuk status 4).

- [ ] Goods: resolve detail (via sub detail → inspection_detail / WT row → detail_inspection_code) → tolak 400 bila lock==1.
- [ ] Material: header lock==1 atau status ∉ [0,1,4] → 400; flip status 0→1 tetap, dan 4 tetap 4 (tidak di-flip).

### Task 4 — Scoping lokasi & IDOR (G-C4, M-C2, G-H3, G-H4) 

**Files:** Modify kedua controller transaksi.

- [ ] Tambah helper privat (pola ResalesGoodsController): `canAccessLocation($locationId)` (GM via hasPermissionSafe('get-only_gm-inspection-goods') atau kode_kantor sama) dan `canViewTransaction` (akses + approver ber-history).
- [ ] Goods: hasPermissionTo→hasPermissionSafe di listing (hapus `without('machineRelation')` yang menyesatkan); guard view di getInspectionGoodShift, mappingInspectionProgress, detailInspectionProgress, getInspectionGoodSubDetailItem, getWallThickness, exportInspectionGoodProgress; guard akses lokasi di add/update/lock; addInspectionGoodsHeader: non-GM dipaksa location_id=kode_kantor.
- [ ] Material: getInspectionMaterial → filter location non-GM + filter tanggal bila from/to dikirim; guard view di getResinInspection, getPelletInspection, exportInspectionMaterials; guard akses di semua mutasi + lock; addInspectionMaterial: non-GM dipaksa location=kode_kantor.

### Task 5 — Generator kode race-safe (G-H2, M-H2)

**Files:** Modify kedua controller.

- [ ] Pola (sudah dipakai di Resales): seluruh generate+insert dalam `DB::transaction`, baris terakhir `lockForUpdate`, parsing `explode('/')` diguard `count>=4 && is_numeric($parts[0]) && bulan==romawi && tahun==yy`, fallback 1. Goods: hitung base sequence detail SEKALI lalu +$i (bukan query di dalam loop).

### Task 6 — Null guard & export fix (G-H5, G-H6, M-H1, getApprovalList)

**Files:** Modify kedua controller + `ApprovalController::getApprovalList` + `public/lims/transaction/inspection/inspection_material.js` (produsen null) + `goods.js`/`inspection_material.js` (avg/approval guard).

- [ ] mappingInspectionProgress & detailInspectionProgress: `$detail` null → 404 JSON; getInspectionGoodSubDetailItem: `$data` kosong → arrays kosong; addWallThickness `$detail` null → 400.
- [ ] getResinInspection/getPelletInspection: `$detail`/header null → 404; JS: `produsen_relation?.name ?? '-'` pola jQuery-safe, `avg` null guard.
- [ ] getApprovalList: header null → 404 JSON (bukan 500).
- [ ] exportInspectionGoodProgress: lookup ApprovalHeader tambah `location => $headers->location_id`; nama file "LIMS_Inspection_Goods_<code>_<ts>.pdf".

### Task 7 — XSS: terapkan escapeHtml (G-C5, M-C5, 0.5)

**Files:** Modify `public/lims/transaction/inspection/goods.js`, `public/lims/transaction/inspection/inspection_material.js`, `public/lims/dashboard/dashboard.js` (Approval Inbox: transaction_code & remark), `public/lims/helper.js` (mappingWT value attr).

- [ ] Semua interpolasi template literal yang memuat data DB/user dibungkus `escapeHtml(...)`: nama relasi, inspection_code (atribut data-*), remark, merk/standard/certificate/no_lot/no_pof, kolom history, label modal, option text.

### Task 8 — Validasi + master Material + QuickChart lokal (M-H4, M-H5, MEDIUM)

**Files:** Modify FormRequests Goods/Material, `InspectionMaterialController` (updateResinDetail validasi; chart), `MaterialController` + requests master, `resources/views/report/inspection/report-resin_material.blade.php`.

- [ ] StoreSubDetailInspectionRequest: wt_1..wt_8 `nullable|numeric`, product/material/machine_id/inspector exists:, detail_inspection_code_id exists:inspection_goods_details,detail_inspection_code. AddInspectionMaterialRequest: type Rule::in([1,2]), amount integer|min:1, weight numeric|min:0, material exists:materials,id, inspector exists:users,id, supplier exists:suppliers,id, produsen_id required_if:type,2.
- [ ] updateResinDetail: validate inspection_code required, step integer, value numeric|between (aman decimal); updateResin/PelletProperties: numeric fields nullable|numeric.
- [ ] MaterialController: gate `canManageModule('view-material')` di add/update/udpateStatus + find null → 404.
- [ ] report-resin_material: ganti `<img src=quickchart>` dengan SVG polyline inline yang dirender dari $data (tanpa internet, tanpa kirim data keluar).

### Task 9 — Tes workflow & verifikasi penuh

**Files:** Create `tests/Feature/InspectionMaterialWorkflowTest.php`, `tests/Feature/InspectionGoodsWorkflowTest.php`.

- [ ] Material (skip bila setting type 2/supplier/material absen): create resin via HTTP → update properties (status 1) → lock (status 2, lock 1, history 1) → double lock 400 → updateResinDetail saat lock 400 → reject (decision 2) → status 4 lock 0 → edit boleh → lock ulang → approve semua step → status 3; approver non-aktif → 422.
- [ ] Goods (skip bila setting type 3 shift 1 absen): create header (3 detail) → add sub detail shift 1 → lock → updateWallThickness saat lock 400 → reject → detail status 0 lock 0 → lock ulang → approve semua step → detail status 2.
- [ ] `php artisan test` penuh hijau; `php -l` & `node --check` semua file berubah; commit per task; merge ke main setelah hijau.
