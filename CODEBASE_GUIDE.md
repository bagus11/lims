# PANDUAN KODINGAN LIMS (LARAVEL PROJECT)
> Patokan utama buat ngoding biar konsisten sama style codebase existing.
> Dibuat: 2026-08-14

---

## 1. STACK TEKNOLOGI

| Komponen | Versi / Package |
|---|---|
| Framework | Laravel 8.75 |
| PHP | ^7.3 \| ^8.0 |
| UI Template | Argon Dashboard (laravel-frontend-presets/argon 1.1.1) |
| Auth | Laravel UI + Spatie Permission ^6.9 |
| API Token | Laravel Sanctum ^2.11 |
| Excel Import/Export | Maatwebsite Excel ^3.1 |
| PDF Export | mPDF ^8.2 |
| Number Converter (Roman) | hnhdigital-os/laravel-number-converter ^1.6 |
| DB | MySQL (primary) + MySQL (secondary = work_order) |
| Frontend Libs | jQuery, DataTables 1.13.1, Select2, Toastr, SweetAlert2, FontAwesome 6.5.1, Bootstrap 4 |
| Locale | Indonesia (id) |
| Timezone | Asia/Jakarta |

---

## 2. STRUKTUR FOLDER (KONVENSI PENAMAAN)

```
app/
├── Models/
│   ├── User.php                        ← Model tanpa subfolder
│   ├── Master/                       ← Data master (produk, material, mesin, dll)
│   │   ├── MasterProduct.php         (Tabel: master_product)
│   │   ├── Material.php
│   │   ├── Machine.php
│   │   ├── CategoryMachine.php
│   │   ├── Supplier.php
│   │   ├── MasterLocation.php
│   │   └── LogMasterProduct.php
│   ├── Setting/                      ← Konfigurasi sistem
│   │   ├── Menus.php
│   │   ├── Submenus.php
│   │   └── Approval/
│   │       ├── ApprovalHeader.php
│   │       └── ApprovalDetail.php
│   └── Transaction/                 ← Data transaksi
│       ├── Goods/
│       │   └── WallThickness.php
│       ├── Inspection/
│       │   ├── ResalesGoodModel.php       ← Ini model buat resales goods (LAMA)
│       │   ├── InspectionGoodsDetail.php
│       │   ├── InspectionGoodsSubDetail.php
│       │   ├── ApprovalHistory.php
│       │   ├── Goods/
│       │   │   ├── SubDetailInspectionGoods.php
│       │   │   ├── WallThicknessLog.php
│       │   │   └── Inspection/
│       │   │       └── InspectionSubDetailLog.php
│       │   └── Material/
│       │       ├── ResinModel.php
│       │       ├── ResinDetail.php
│       │       └── Pellet.php
│       ├── Insspection/              ← TYPO "Insspection" (doank pake namespace ini)
│       │   └── InspectionGood.php
│       └── Material/
│           └── InspectionMaterial.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                     ← Login, Register, dll (bawaan laravel/ui)
│   │   ├── Master/                   ← CRUD MasterProductController, MaterialController, dll
│   │   ├── Setting/                  ← Setting: Menus, RolePermission, UserAccess, Approval
│   │   ├── Transaction/
│   │   │   └── Inspection/           ← GoodsController, InspectionMaterialController, ResalesGoodsController
│   │   ├── DashboardController.php
│   │   ├── ProfileController.php
│   │   ├── UserController.php
│   │   └── HomeController.php
│   │
│   └── Requests/                     ← Form Request Validation
│       ├── Master/
│       │   ├── MasterProduct/UpdateProductRequest.php
│       │   ├── Material/StoreMaterialRequest.php
│       │   ├── Materialial/UpdateMaterialRequest.php   ← TYPO: "Masterial
│       │   ├── Machine/StoreMachineRequest.php
│       │   └── CategoryMachine/
│       ├── Setting/
│       │   ├── Menus/
│       │   ├── Approval/
│       │   ├── RolePermission/
│       │   ├── RolePermisison/      ← TYPO: "Permisison
│       │   └── UserAccess/
│       ├── Transaction/
│       │   └── Inspection/
│       │       ├── Goods/
│       │       └── Material/
│       ├── UserRequest.php
│       ├── ProfileRequest.php
│       └── PasswordRequest.php
│
├── Helper/
│   └── ResponseFormatter.php         ← Standard response API wrapper
│
└── Imports/
    └── Master/
        └── UploadMasterProduct.php   ← Maatwebsite Excel Import

resources/views/
├── layouts/
│   ├── app.blade.php                ← MAIN LAYOUT
│   ├── headers/cards.blade.php
│   ├── navbars/ (sidebar, navbar, navs/auth, navs/guest)
│   └── footers/ (auth, nav, guest)
├── master/
│   └── {nama_modul}/
│       ├── {modul}-index.blade.php         ← Halaman utama
│       └── modal/
│           ├── add-{modul}.blade.php
│           └── edit-{modul}.blade.php
├── transaction/inspection/{modul}/
│   ├── {modul}-index.blade.php
│   └── modal/
├── setting/{modul}/
│   ├── {modul}-index.blade.php
│   └── modal/
├── report/inspection/
├── dashboard/modal/approval/
└── auth/

public/lims/
├── helper.js                         ← Global JS helpers (saveHelper, getCallback, postAttachment, dll)
├── master/{modul}/{modul}.js
├── transaction/inspection/{modul}.js
└── setting/{modul}/{modul}.js

database/migrations/
└── YYYY_MM_DD_HHMMSS_create_{nama_tabel}_table.php
```

---

## 3. MODEL PATTERNS

### 3.1 Struktur Model Standar

```php
<?php

namespace App\Models\Master;  // Namespace sesuai folder

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaModel extends Model
{
    use HasFactory;

    // HANYA jika nama tabel TIDAK sesuai plural convention:
    protected $table = 'nama_tabel';

    // SELALU PAKAI guarded = [] (bukan fillable)
    protected $guarded = [];

    // Nama relasi: suffix "Relation", hasOne, params (related, foreignKey, ownerKey)
    function namaRelation() {
        return $this->hasOne(RelatedModel::class, 'id', 'foreign_key_id');
    }
}
```

### 3.2 Konvensi Relasi
- Nama method relasi: suffix `Relation` (contoh: `categoryRelation`, `locationRelation`, `userRelation`, `productRelation`)
- Jarang pake `belongsTo`, lebih sering `hasOne` kebalik
- Parameter: `hasOne(Class::class, 'id', 'foreignkey_kolom')`
- Relasi di eager load pake `with(['namaRelation'])`

### 3.3 Catatan Penting
- SEMUA model wajib `use HasFactory`
- GA PAKAI `$fillable`, tapi `protected $guarded = []`
- `$table` di-set hanya kalo nama tabel beda dengan convention (contoh: `MasterProduct` → `master_product` bukan `master_products`)

---

## 4. CONTROLLER PATTERNS

### 4.1 Struktur Controller Standar

```php
<?php

namespace App\Http\Controllers\Kategori;  // Master|Setting|Transaction\Inspection

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Path\Ke\FormRequestClass;
use App\Models\Path\Ke\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamaController extends Controller
{
    // 1. Method index: return view
    function index() {
        return view('path.ke.view-index');
    }

    // 2. Method GET data untuk DataTables / select option
    function getData(Request $request) {
        // Cek permission dengan hasPermissionTo (jika perlu)
        if(auth()->user()->hasPermissionTo('nama-permission')){
            $data = Model::with([...])->get();
        }else{
            $data = Model::with([...])
                ->where('location_id', auth()->user()->kode_kantor)
                ->get();
        }
        return response()->json([
            'data' => $data
        ]);
    }

    // 3. Method CREATE/STORE data (dengan FormRequest)
    function addNama(Request $request, FormRequestClass $formRequest) {
        // try-catch biasanya di-comment out (kebiasaan existing)
        $formRequest->validated();

        // Logika bisnis disini...
        $post = [
            'kolom' => $request->nilai,
        ];

        // JIKA operasi multi-table, SELALU pakai DB::transaction
        DB::transaction(function() use($post, $request) {
            Model::create($post);
            // ...operasi lain
        });

        // Selalu return ResponseFormatter
        return ResponseFormatter::success(
            $post,
            'Message success'
        );
    }

    // 4. Method UPDATE
    // 5. Method DELETE
    // 6. Method EXPORT (jika perlu)
}
```

### 4.2 ResponseFormatter Wrapper
- `ResponseFormatter::success($data, $message)` → meta.code 200
- `ResponseFormatter::error($data, $message, $code=400)` → meta.status error
- Structure response:
```json
{
  "meta": { "code": 200, "status": "success", "message": "..." },
  "data": {}
}
```

### 4.3 Catatan Penting
- Semua try-catch di-comment out, tinggal uncomment kalo sudah mau production
- Validasi selalu lewat FormRequest (type-hint di parameter) + `$formRequest->validated()`
- Multi-table update/create selalu DB::transaction
- Untuk ambil user login: `auth()->user()->id` atau `auth()->id()`
- Cek permission: `auth()->user()->hasPermissionTo('permission-name')`
- Filter by lokasi user: `auth()->user()->kode_kantor`
- Filter by date: `->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])`
- Kode transaksi manual dibuat di controller logic (bukan migration) dengan pattern romawi bulan

### 4.4 Generate Transaction Code Pattern
```php
$increment = Model::orderBy('id','desc')->first();
$date_month = strtotime(date('Y-m-d'));
$month = idate('m', $date_month);
$year = idate('y', $date_month);
$month_convert = NumConvert::roman($month);
// Pattern: {nomor}/{KODE}/{ROMAWI}/{BULAN_ROMAWI}/{tahun 2 digit}
// Contoh: 1/INSG/IV/24
```

---

## 5. FORM REQUEST PATTERNS

```php
<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalHeaderRequest extends FormRequest
{
    // authorize() return true atau auth()->check()
    public function authorize()
    {
        return true; // atau auth()->check() untuk profile request
    }

    // rules(): array sederhana, string format
    public function rules()
    {
        return [
            'location' => 'required',
            'inspection_type' => 'required',
        ];
    }
}
```

- Tidak ada custom messages di existing code
- authorize() umumnya return true
- Penamaan: `Store{Nama}Request`, `Update{Nama}Request`, kadang `Add{Nama}Request`

---

## 6. ROUTING PATTERNS

### 6.1 Struktur Routes (web.php)
```php
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // Semua route di-dalam grup ini

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('/');

    // Master - pola penamaan:
    Route::get('/url-kebab-case', 'Controller@index')->name('nama_route_snake_case');
    Route::get('/getNama', 'Controller@getData')->name('getNama');
    Route::post('/addNama', 'Controller@addNama')->name('addNama');
    Route::post('/updateNama', 'Controller@updateNama')->name('updateNama');
    Route::get('/deleteNama', 'Controller@deleteNama')->name('deleteNama');
    Route::get('/getDetailNama', 'Controller@getDetail')->name('getDetailNama');

    // Export
    Route::get('exportNama/{id}','Controller@exportNama');
});
```

### 6.2 Konvensi
- URL: kebab-case (contoh: `/inspection-goods`, `/master_product` → tapi master_product pake underscore, tidak konsisten)
- Route name: snake_case
- Semua non-auth route di dalam `Route::group(['middleware' => 'auth'])`
- Lebih banyak pakai `'Controller@method'` string syntax daripada `[Class::class, 'method']`

---

## 7. MIGRATION & DATABASE PATTERNS

### 7.1 Struktur Migration Standar

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNamaTable extends Migration
{
    public function up()
    {
        Schema::create('nama_tabel', function (Blueprint $table) {
            $table->id();
            $table->string('kolom_string');
            $table->string('kolom_nullable')->nullable();
            $table->integer('kolom_integer');
            $table->text('kolom_text');
            $table->json('kolom_json')->nullable(); // untuk spesification dll
            $table->timestamps(); // created_at, updated_at otomatis

            // Untuk foreign key (ADA di migration baru, GA ADA di migration lama)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nama_tabel');
    }
}
```

### 7.2 Konvensi Tabel & Kolom
- **Nama tabel**: snake_case, plural atau singular? TIDAK KONSISTEN. Lihat existing:
  - `master_product` (singular, bukan master_products)
  - `materials` (plural)
  - `machines` (plural)
  - `suppliers` (plural)
  - `menus` (plural)
  - `inspection_goods` (plural)
  - `inspection_goods_details` (plural)
  - `approval_headers` (plural)
  - `approval_details` (plural)
  - `wall_thicknesses` (plural)
  - `resales_good_models` (plural, migration lama)
  - **Kesimpulan**: cenderung PLURAL, tapi `master_product` SINGULAR (pengecualian)**

- **Primary key**: selalu `$table->id()` (auto-inc bigint unsigned)
- **Foreign key**: pattern existing TIDAK define `foreign()` di migration lama, cuma kolom `integer('something_id'). Di migration BARU saya tambahkan foreign (karena lo minta FK)
- **Unique code**: `$table->string('transaction_code')->unique()` untuk kode transaksi
- **Kolom spesification dengan child symbol/value**: pakai `$table->json('spesification')->nullable()`
- **Timestamps**: selalu `$table->timestamps()`

### 7.3 Kolom Umum
- `status`: integer (0 = new/draft, 1 = in progress, 2 = approval, 3 = done/approved)
- `created_by`: `unsignedBigInteger` atau `integer` user_id
- `user_id`: untuk creator (dipakai bergantian dengan created_by, tidak konsisten)
- Pattern log tabel: biasanya ada suffix `_log` tabel dengan struktur mirip induk + `user_id`, `remark`, `creator`

---

## 8. BLADE VIEW PATTERNS

### 8.1 Struktur View Utama
```blade
@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    {{-- Container row mx-2 mt-4 --}}
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Judul Halaman</strong>
                    </div>
                    <div class="col-6">
                        {{-- Tombol aksi di kanan --}}
                        <button class="btn btn-sm btn-success mr-2" style="float: right">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="nama_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="text-align: center">Kolom 1</th>
                            <th scope="col" style="text-align: center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
@endsection

@include('folder.nama_modal.modal-name')
@include('folder.nama_modal.modal-edit')

@push('js')
    <script src="{{ asset('lims') }}/path/ke/file.js"></script>
@endpush
```

### 8.2 Konvensi View
- **Layout**: `@extends('layouts.app')` selalu
- **Header cards**: selalu `@include('layouts.headers.cards')`
- **Table class**: `datatable-stepper` (custom styling)
- **Card styling**: border-radius 20px (di define global CSS di app.blade.php)
- **Button utama**: btn-sm btn-success (+) hijau, btn-info (info, btn-warning
- **Modal**: diletakkan SETELAH `@endsection` dan sebelum `@push('js')`
- **JS**: di-push via `@push('js')` dan load dari `public/lims/...`

### 8.3 Modal Pattern
```blade
<div class="modal fade" id="modalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formId" method="POST" action="{{ route('nama_route') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Judul</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <p for="">Label Field</p>
                            <input type="text" id="field_id" name="field_name" class="form-control">
                            <span class="field_name_error text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_submit_id" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### 8.4 Form Field Pattern
- Label: pakai `<p for="">Label</p>` bukan `<label>`
- Error display: `<span class="field_name_error text-danger"></span>` (prefix nama field + `_error`
- Select2: `<select class="select2">
- Untuk filter dropdown (date from-to):
  - Default from: 1 bulan lalu
  - Default to: hari ini

---

## 9. JAVASCRIPT / AJAX PATTERNS (JQUERY)

### 9.1 Helper Functions (dari helper.js)

| Function | Kegunaan |
|---|---|
| `getCallback(url, data, callback)` | GET request + Swal loading |
| `getCallbackNoSwal(url, data, callback)` | GET request tanpa Swal loading |
| `postAttachment(url, formData, bool, callback)` | POST file upload |
| `saveHelper(url, data, redirectRoute)` | POST + redirect setelah sukses |
| `saveRepo(url, data, redirectRoute)` | POST alternatif |
| `SwalLoading('message')` | SweetAlert loading spinner |

### 9.2 Inisialisasi DataTables
```javascript
function mappingTable(data) {
    $('#master_product_table').DataTable({
        data: data,
        destroy: true,
        columns: [
            {data: 'seri', className: 'text-center'},
            {
                data: 'id', className: 'text-center',
                render: function(data, type, row) {
                    return `<button class="btn btn-sm btn-primary edit" data-id="'+row['id']+'"><i class="fas fa-edit"></i></button>';
                }
            }
        ],
        order: [[0, 'asc']
    });
}
```

### 9.3 Ajax Submit Pattern Standar
```javascript
$('#btn_id').on('click', function(e){
    e.preventDefault();
    var data = {
        'field1' : $('#field1').val(),
        'field2' : $('#field2').val(),
    }
    saveHelper("{{ route('addNama') }}", data, "{{ route('nama_index') }}");
})
```

### 9.4 Toastr Notifikasi
```javascript
toastr['success']('Pesan sukses');
toastr['error']('Pesan error');
toastr['warning']('Pesan warning');
```

### 9.5 Catatan JS
- Selalu pake jQuery select2 untuk select
- SweetAlert2 untuk loading
- Error 422: otomatis inject ke span.{key}_error (dari helper.js)
- Sebelum reload table di-destroy dulu baru re-init (`.DataTable())
- CSRF token diambil dari meta[name="csrf-token"]

---

## 10. EXPORT / IMPORT PATTERNS

### 10.1 Excel Import (Maatwebsite)
```php
// app/Imports/Master/UploadMasterProduct.php
namespace App\Imports\Master;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UploadMasterProduct implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    public function model(array $row)
    {
        // Logic import disini, bisa cek existing + delete lalu insert
        return new MasterProduct([...]);
    }
}
```

Di Controller:
```php
Excel::import(new UploadMasterProduct, $request->file('upload_file'));
```

### 10.2 PDF Export (mPDF)
```php
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLHeader($header_html);
$mpdf->SetHTMLFooter($footer_html);
$mpdf->AddPage('P', '', '', '', '', 2, 2, 18, 18, 2, 0);
$mpdf->WriteHTML(view('report.view_name', $data_array));
ob_clean();
$mpdf->Output('Nama File.pdf', 'I'); // I = inline di browser
```

Margin: left=2, right=2, top=18, bottom=18, header=2, footer=0

---

## 11. PERMISSION & AUTH PATTERNS

### 11.1 Spatie Permission
- Cek permission: `auth()->user()->hasPermissionTo('permission-name')`
- Cek role: `auth()->user()->hasRole('nama-role')`
- Assign role: `$user->assignRole('role')`
- Permission names: kebab-case, contoh: `get-only_gm-inspection-goods`

### 11.2 Kolom User Khusus
- `auth()->user()->kode_kantor` → untuk filter lokasi/office

---

## 12. NAMING CONVENTIONS RINGKAS

| Item | Convention | Contoh |
|---|---|---|
| **Class (Model)** | PascalCase | `MasterProduct`, `WallThickness` |
| **Class (Controller)** | PascalCase + suffix Controller | `MasterProductController`, `GoodsController` |
| **Method** | camelCase | `getMasterProduct()`, `addInspectionGoodsHeader()` |
| **Variable** | camelCase | `$ticketCode`, `$incrementCode` |
| **Table DB** | snake_case (plural kecuali `master_product`) | `inspection_goods`, `approval_headers` |
| **Kolom DB** | snake_case | `transaction_code`, `created_by` |
| **View Folder** | kebab-case atau snake_case | `master_product`, `inspection/goods` |
| **View File** | kebab-case dengan suffix | `master_product-index.blade.php` |
| **Modal File** | kebab-case prefix | `add-header-resales-goods.blade.php` |
| **JS File** | snake_case atau kebab-case | `master_product.js`, `resales_goods.js` |
| **Route URL** | campuran (lihat 6.2) | `/master_product`, `/inspection-goods` |
| **Route Name** | snake_case | `getMasterProduct`, `addInspectionGoodsHeader` |
| **FormRequest File** | PascalCase prefix Store/Update | `StoreApprovalHeaderRequest.php` |

---

## 13. HAL YANG PERLU DIPERHATIKAN (WARNING ⚠️)

1. **TYPO DI NAMESPACE/FOLDER**:
   - `Insspection` (dobel s) di `app/Models/Transaction/Insspection/`
   - `Masterial` di `app/Http/Requests/Master/Masterial/` (harusnya Material)
   - `Permisison` di `app/Http/Requests/Setting/RolePermisison/` (harusnya Permission)
   - Kalau buat folder baru, JANGAN ikut typo diatas. Tetap ikut ejaan BENAR.

2. **Migration TIDAK punya foreign key** di codebase lama (cuma integer kolom). Migration baru barusan saya tambahin FK. Kalo lo tiak mau FK bisa dihapus.

3. **Try-catch SEMUA di-comment out**. Kebiasaan author: comment dulu kalo develop, uncomment kalo production.

4. **`$guarded = []` BUKAN `$fillable`**. Jangan salah pake.

5. **ResponseFormatter** untuk response JSON selalu. Kecuali untuk method yang cuma return view atau JSON ke DataTables (pake `response()->json(['data' => $data])`).

6. **JS selalu jQuery**, TIDAK ada Vue/React/Alpine. Jangan bikin frontend framework baru ya.

7. **Tanggal format**: `date('Y-m-d H:i:s')` untuk created_at manual (bukan Carbon, tapi AppServiceProvider udah set locale id + timezone Jakarta).

---

## 14. STATUS CODE PENTING YANG SERING DIPAKAI

| Code | Arti | Keterangan |
|---|---|---|
| 0 | New / Draft | Status awal transaksi |
| 1 | In Progress | Sedang dikerjakan / diapprove step 1 |
| 2 | Approval / Done | Selesai / Approved final |
| 3 | Locked / Approved | Dipakai dibeberapa tempat |
| inspection_type 2 | Material Inspection | Resin & Pellet |
| inspection_type 3 | Goods Inspection | Pipa / Goods |
| type 1 | Header | Approval workflow |
| type 2 | Detail? | Pellet flow |

---

## 15. CONTOH IMPLEMENTASI LENGKAP (Resales Goods)

Buat modul baru (sesuai request database yang barusan dibuat):

1. **Model** (3 model):
   - `app/Models/Master/MasterProductResalesGood.php` → tabel `master_product_resales_goods`
   - `app/Models/Transaction/Inspection/ResalesGood.php` → tabel `resales_goods`
   - `app/Models/Transaction/Inspection/ResalesGoodDetail.php` → tabel `resales_goods_detail`

2. **Controllers**: `app/Http/Controllers/Transaction/Inspection/ResalesGoodsController.php` → sudah ada tapi isinya cuma index + get. BISA di-extend

3. **Form Requests**:
   - `app/Http/Requests/Transaction/Inspection/ResalesGoods/StoreResalesGoodsRequest.php`
   - `app/Http/Requests/Transaction/Inspection/ResalesGoods/StoreResalesGoodsDetailRequest.php`

4. **Views**:
   - `resources/views/transaction/inspection/resales-goods/resales-goods-index.blade.php` → SUDAH ADA
   - Modal tambah modal add/edit

5. **JS**: `public/lims/transaction/inspection/resales_goods.js` → SUDAH ADA tinggal extend

6. **Routes**: routes/web.php baris 148-149 → SUDAH ADA tinggal tambah route add/update.

---

**END OF DOCUMENT**
