# PROJECT RULES: LIMS (PT PRALON)
> **MUST FOLLOW EVERY TIME** - Selalu ikuti rules dibawah ini SETIAP kali ngoding di project LIMS ini.
> Update terakhir: 2026-08-14

---

## 1. FOLDER & PENEMPATAN FILE (WAJIB SESUAI)

| Tipe File | Folder Path (Exact) | Convention Nama File |
|---|---|---|
| Model (Master Data) | `app/Models/Master/` | PascalCase singular: `MasterProduct.php`, `Material.php` |
| Model (Setting) | `app/Models/Setting/` atau `app/Models/Setting/Approval/` | PascalCase: `Menus.php`, `ApprovalHeader.php` |
| Model (Transaction) | `app/Models/Transaction/Inspection/` atau `Goods/` / `Material/` | **JANGAN buat folder typo `Insspection`** (dobel s) - itu typo existing. Folder BARU = `Inspection` |
| Model (User) | `app/Models/User.php` | Jangan di-subfolder |
| Controller (Master) | `app/Http/Controllers/Master/` | PascalCase + `Controller`: `MasterProductController.php` |
| Controller (Setting) | `app/Http/Controllers/Setting/` | `MenusController.php`, `ApprovalController.php` |
| Controller (Transaction) | `app/Http/Controllers/Transaction/Inspection/` | `GoodsController.php`, `ResalesGoodsController.php` |
| Form Request (Master) | `app/Http/Requests/Master/{SubModul}/` | **JANGAN ikut typo `Masterial`** → harusnya `Material` |
| Form Request (Setting) | `app/Http/Requests/Setting/{SubModul}/` | **JANGAN ikut typo `Permisison`** → harusnya `Permission` |
| Form Request (Transaction) | `app/Http/Requests/Transaction/Inspection/{SubModul}/` | `StoreHeaderRequest.php` |
| Helper | `app/Helper/` | `ResponseFormatter.php` (SUDAH ADA, jangan buat baru tanpa alasan kuat) |
| Excel Import | `app/Imports/Master/` atau sesuai kategori | PascalCase prefix Upload: `UploadMasterProduct.php` |
| Migration | `database/migrations/` | `YYYY_MM_DD_HHMMSS_create_{nama_tabel}_table.php` |
| View (Master) | `resources/views/master/{modul}/` | `master_product-index.blade.php` |
| View (Setting) | `resources/views/setting/{modul}/` | `approval-index.blade.php` |
| View (Transaction) | `resources/views/transaction/inspection/{modul}/` | `goods-index.blade.php`, `resales-goods-index.blade.php` |
| View Modal | `resources/views/{kategori}/{modul}/modal/` | Prefix `add-` / `edit-` |
| View Report PDF | `resources/views/report/inspection/` | `report_inspection_goods.blade.php` |
| Custom JS | `public/lims/{kategori}/{modul}/` | `master_product.js`, `resales_goods.js` |
| Global JS Helper | `public/lims/helper.js` | SUDAH ADA - JANGAN diubah isi |

---

## 2. MODEL PATTERN (WAJIB)

```php
<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaModel extends Model
{
    use HasFactory; // SELALU ADA

    // Set $table HANYA jika nama tabel TIDAK sesuai convention
    protected $table = 'master_product'; // contoh: tabel master_product (bukan master_products)

    // SELALU PAKAI INI, JANGAN PAKAI $fillable
    protected $guarded = [];

    // Relasi suffix "Relation" selalu
    function categoryRelation() {
        return $this->hasOne(CategoryMachine::class, 'id', 'category_id');
    }
}
```
⚠️ **JANGAN GUNAKAN `$fillable`** — selalu `protected $guarded = []`

---

## 3. CONTROLLER PATTERN (WAJIB)

```php
<?php
namespace App\Http\Controllers\Kategori; // Master | Setting | Transaction\Inspection

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Path\FormRequestClass;
use App\Models\Path\Ke\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamaController extends Controller
{
    // [1] index() SELALU return view
    function index() {
        return view('path.ke.view-index');
    }

    // [2] getData() untuk DataTables / select option
    function getData(Request $request) {
        // Filter lokasi user jika perlu:
        // ->where('location_id', auth()->user()->kode_kantor)
        $data = Model::with(['namaRelation'])->get();
        return response()->json(['data' => $data]);
    }

    // [3] addNama() dengan FormRequest type-hint
    function addNama(Request $request, FormRequestClass $formRequest) {
        // try-catch DICOMMENT OUT (sesuai kebiasaan existing)
        // try {
            $formRequest->validated(); // validated() WAJIB

            $post = ['kolom' => $request->nilai];

            // Multi-table? WAJIB DB::transaction
            DB::transaction(function() use($post, $request) {
                Model::create($post);
            });

            // Selalu pakai ResponseFormatter
            return ResponseFormatter::success($post, 'Message sukses');
        // } catch (\Throwable $th) {
        //     return ResponseFormatter::error($th, 'Message gagal', 500);
        // }
    }
}
```

✅ Response structure: `{ meta: {code, status, message}, data: {} }`

---

## 4. FORM REQUEST PATTERN

```php
<?php
namespace App\Http\Requests\Setting\Approval;
use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalHeaderRequest extends FormRequest
{
    public function authorize() { return true; } // atau auth()->check()

    public function rules()
    {
        return [
            'location' => 'required',
            'inspection_type' => 'required',
        ];
    }
}
```
✅ Nama class: `Store{Nama}Request` / `Update{Nama}Request` / `Add{Nama}Request`
✅ Tidak perlu custom `messages()`

---

## 5. MIGRATION & DATABASE PATTERN

```php
Schema::create('nama_tabel', function (Blueprint $table) {
    $table->id(); // SELALU untuk PK
    $table->string('kolom_string');
    $table->string('opsional')->nullable();
    $table->integer('kolom_integer');
    $table->text('kolom_panjang');
    // Kolom spesification (child symbol+value) SELALU pakai JSON:
    $table->json('spesification')->nullable();
    // Foreign key:
    $table->unsignedBigInteger('created_by');
    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    $table->timestamps(); // SELALU ADA created_at + updated_at
});
```

Konvensi tabel:
- **Plural** (umum): `materials`, `machines`, `inspection_goods`, `approval_headers`
- **Singular PENGEKUALIAN**: `master_product` (bukan master_products)
- Kode unik: `$table->string('transaction_code')->unique()`

---

## 6. BLADE VIEW PATTERN

```blade
@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards') {{-- SELALU ADA --}}
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><strong class="cardTitle">Judul</strong></div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-success mr-2" style="float:right">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="table_id">
                    <thead class="thead-light">
                        <tr><th style="text-align:center">Kolom</th></tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
@endsection
@include('folder.modul.modal-name')
@push('js')
    <script src="{{ asset('lims') }}/path/ke/file.js"></script>
@endpush
```

✅ Table class: SELALU `datatable-stepper` (custom styling header warna `#C96868`)
✅ Label form: `<p for="">` BUKAN `<label>`
✅ Error span: `<span class="field_name_error text-danger"></span>`

---

## 7. JAVASCRIPT / JQUERY (PURE JQUERY SAJA)
⚠️ **DILARANG** pakai Vue/React/Alpine atau framework lain. Existing PURE jQuery.

Helper function dari `public/lims/helper.js` (SELALU ADA):
```javascript
// === [1] GET DATA (swal loading otomatis) ===
getCallback('nama_route', {data}, function(response){
    swal.close()          // selalu
    mappingTable(response.data)
})

// === [2] GET DATA tanpa Swal (misal setelah add/update) ===
getCallbackNoSwal('route', null, function(response){
    mappingTable(response.data)
})

// === [3] POST / CREATE / UPDATE — ⚠️ WAJIB PAKAI INI (BUKAN saveHelper) ===
// Referensi pattern: public/lims/transaction/inspection/inspection_material.js line 40-62
postCallback('addNamaAtauUpdateNama', {data: ...}, function(response){
    swal.close()                                                // [A] close swal
    toastr['success'](response.meta.message)                    // [B] toast sukses
    $('#modalNama').modal('hide')                               // [C] hide modal
    getCallbackNoSwal('getListData', null, function(resp){      // [D] refresh table
        mappingTable(resp.data)
    })
    // Reset form fields jika perlu:
    // $('#field').val('')
    // $('.field_error').html('')
})

// === [4] UPLOAD FILE (POST dengan FormData) ===
postAttachment('uploadRoute', formData, true, function(response){
    swal.close()
    toastr['success'](response.meta.message)
})

// === [5] LAINNYA ===
SwalLoading('Please wait ...')
swal.close()
toastr['success']('Pesan') / toastr['error']('Pesan')
```

**⚠️ ATURAN PENTING POST/UPDATE JS:**
1. **JANGAN GUNAKAN `saveHelper()` untuk Add / Update.**
   saveHelper = cara lama. Yang STANDARD sekarang = `postCallback()` dengan callback anonymous function yang berisi 4 langkah tetap (A→B→C→D) seperti pattern di inspection_material.js L56-62.
2. Struktur callback postCallback SELALU 4 langkah berurutan:
   A. `swal.close()`
   B. `toastr['success'](response.meta.message)`
   C. `$('#ModalNya').modal('hide')`
   D. `getCallbackNoSwal(...)` → refresh table / reload data
3. Add button, Edit button, Delete button: semuanya pakai pattern `postCallback()` + 4 langkah callback.
   Delete: tambah confirm() sebelum jalankan postCallback.
4. File Upload / Excel Import TETEP pakai `postAttachment()` (karena butuh FormData + multipart).

DataTables pattern:
```javascript
function mappingTable(data) {
    $('#table_id').DataTable({
        data: data,
        destroy: true, // SELALU destroy sebelum re-init
        columns: [
            {data: 'kolom', className: 'text-center'},
            {data: 'id', render: function(data,type,row){
                return `<button class="btn edit" data-id="${row['id']}"><i class="fas fa-edit"></i></button>`
            }}
        ]
    })
}
```

---

## 8. EXPORT PATTERN (Excel & PDF)

**Excel Import (Maatwebsite):**
```php
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// Di controller: Excel::import(new UploadClass, $request->file('upload_file'));
```

**PDF Export (mPDF) margin TETAP:**
```php
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLHeader($header); // logo PT Pralon
$mpdf->SetHTMLFooter($footer); // disclaimer + page
$mpdf->AddPage('P','','','','', 2, 2, 18, 18, 2, 0); // margin JANGAN diubah
$mpdf->WriteHTML(view('report.view_name', $data));
ob_clean();
$mpdf->Output('Nama File.pdf', 'I');
```

---

## 9. ROUTING PATTERN

- Semua route (kecuali auth) di dalam `Route::group(['middleware' => 'auth'], function () { ... })`
- Route name: **snake_case** (`getMasterProduct`, `addInspectionGoodsHeader`)
- Controller route syntax string: `'Controller@method'` (bukan array Class::class)
- Contoh:
  ```php
  Route::get('/master_product', 'Controller@index')->name('master_product');
  Route::post('/addProduct', 'Controller@addProduct')->name('addProduct');
  ```

---

## 10. PERMISSION & AUTH

```php
if (auth()->user()->hasPermissionTo('nama-permission')) { ... }
auth()->user()->id            // ID user
auth()->user()->kode_kantor   // Filter data per lokasi/office
auth()->check()               // Cek sudah login
```

---

## ⚠️ TYPO EXISTING JANGAN DITIRU

| ❌ TYPO EXISTING | ✅ NAMA BENAR (file BARU) | Lokasi |
|---|---|---|
| `Insspection` | `Inspection` | `app/Models/Transaction/Insspection/` |
| `Masterial` | `Material` | `app/Http/Requests/Master/Masterial/` |
| `Permisison` | `Permission` | `app/Http/Requests/Setting/RolePermisison/` |

> Catatan: File existing yang sudah pakai typo biarkan. HANYA file BARU = ejaan BENAR.

---

## 🎯 STATUS CODE REFERENSI CEPAT

| Kode | Arti |
|---|---|
| `status = 0` | New / Draft |
| `status = 1` | In Progress |
| `status = 2` | Approval / Selesai (Done) |
| `status = 3` | Locked / Final Approved |
| `inspection_type = 2` | Material Inspection (Resin + Pellet) |
| `inspection_type = 3` | Goods Inspection (Pipa) |
| `type = 1` | Header approval workflow |
| `type = 2` | Detail / Pellet flow |

---

## 📝 CHECKLIST FINAL SEBELUM SELESAI NGODING

Sebelum bilang "selesai", selalu cek:

- [ ] File di folder YANG BENAR (sesuai tabel section 1)
- [ ] Namespace PHP sesuai folder (tidak ada typo Insspection/Masterial/Permisison)
- [ ] Model: `use HasFactory` + `protected $guarded = []` (bukan fillable)
- [ ] Controller: index() return view, add/update pake FormRequest + validated()
- [ ] Response sukses/gagal pake `ResponseFormatter::success/error`
- [ ] Multi-table operasi: `DB::transaction(function() use(...) {})`
- [ ] Try-catch di-comment out (sesuai gaya existing)
- [ ] View extend `layouts.app` + include `layouts.headers.cards`
- [ ] Custom JS di-load via `@push('js')` dari `public/lims/...`
- [ ] ⚠️ Add / Update / Delete handler di JS **WAJIB pakai `postCallback()` dengan callback 4 langkah (swal.close → toastr success → modal hide → getCallbackNoSwal refresh table)**.
- [ ] ❌ JANGAN pakai `saveHelper()` untuk add/update/delete. saveHelper = deprecated.
- [ ] Migration ada `$table->timestamps()`
- [ ] Jangan buat file .md dokumentasi baru KECUALI user YANG MEMINTA

---

**END OF RULES. UNTUK DETAIL LEBIH LENGKAP BUKA `CODEBASE_GUIDE.md` DI ROOT PROJECT.**
