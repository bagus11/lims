@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')

<style>
    /* Semua kelas diberi awalan "manual-" supaya tidak bentrok dengan
       utility Bootstrap/Argon yang dipakai halaman lain. */
    .manual-page {
        --manual-ink: #101c1a;
        --manual-soft: #566b67;
        --manual-faint: #7d8f8b;
        --manual-rule: #dfe6e4;
        --manual-accent: #0c6b63;
        --manual-accent-soft: #e3f2ef;
        --manual-alert: #a32c18;
        --manual-alert-soft: #fbeae6;
        --manual-good: #1d7a45;
        color: var(--manual-ink);
        font-size: 14px;
        line-height: 1.7;
    }
    .manual-page .manual-hero {
        background: #fff;
        border: 1px solid var(--manual-rule);
        border-radius: 14px;
        padding: 30px 34px;
        margin-bottom: 20px;
    }
    .manual-page .manual-eyebrow {
        font-size: 10px;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: var(--manual-accent);
        font-weight: 700;
        margin-bottom: 10px;
    }
    .manual-page h1.manual-title {
        font-size: 30px;
        font-weight: 700;
        letter-spacing: -.02em;
        margin: 0 0 10px;
        color: var(--manual-ink);
    }
    .manual-page .manual-standfirst {
        font-size: 15px;
        color: var(--manual-soft);
        max-width: 70ch;
        margin: 0;
    }
    .manual-page .manual-roles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 18px;
        margin-top: 26px;
        padding-top: 22px;
        border-top: 1px solid var(--manual-rule);
    }
    .manual-page .manual-roles dt {
        font-size: 10px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--manual-faint);
        margin-bottom: 4px;
    }
    .manual-page .manual-roles dd { margin: 0; font-size: 13px; color: var(--manual-soft); }

    /* daftar isi */
    .manual-page .manual-toc {
        background: #fff;
        border: 1px solid var(--manual-rule);
        border-radius: 14px;
        padding: 22px 26px;
        margin-bottom: 26px;
    }
    .manual-page .manual-toc h2 {
        font-size: 11px;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--manual-faint);
        margin: 0 0 14px;
    }
    .manual-page .manual-toc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px 26px;
    }
    .manual-page .manual-toc-col strong {
        display: block;
        font-size: 12px;
        color: var(--manual-ink);
        margin-bottom: 7px;
    }
    .manual-page .manual-toc-col a {
        display: block;
        font-size: 13px;
        color: var(--manual-soft);
        padding: 3px 0 3px 12px;
        border-left: 2px solid var(--manual-rule);
        text-decoration: none;
    }
    .manual-page .manual-toc-col a:hover,
    .manual-page .manual-toc-col a:focus {
        color: var(--manual-accent);
        border-left-color: var(--manual-accent);
        text-decoration: none;
    }

    /* bagian */
    .manual-page .manual-part {
        margin: 34px 0 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--manual-ink);
        font-size: 12px;
        letter-spacing: .14em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--manual-ink);
    }
    .manual-page .manual-card {
        background: #fff;
        border: 1px solid var(--manual-rule);
        border-radius: 14px;
        padding: 26px 30px;
        margin-bottom: 18px;
        scroll-margin-top: 90px;
    }
    .manual-page .manual-card h3 {
        font-size: 19px;
        font-weight: 700;
        margin: 0 0 3px;
        color: var(--manual-ink);
    }
    .manual-page .manual-path {
        font-family: ui-monospace, Menlo, Consolas, monospace;
        font-size: 11px;
        color: var(--manual-faint);
        margin-bottom: 16px;
    }
    .manual-page .manual-card h4 {
        font-size: 13px;
        font-weight: 700;
        margin: 22px 0 8px;
        color: var(--manual-ink);
    }
    .manual-page .manual-card p { margin: 0 0 12px; max-width: 78ch; }
    .manual-page .manual-card ul { padding-left: 18px; margin: 0 0 12px; max-width: 78ch; }
    .manual-page .manual-card li { margin-bottom: 5px; }
    .manual-page code {
        font-family: ui-monospace, Menlo, Consolas, monospace;
        font-size: 12px;
        background: #eef3f1;
        border: 1px solid var(--manual-rule);
        border-radius: 4px;
        padding: 1px 5px;
        color: var(--manual-ink);
    }

    /* gambar dokumentasi */
    .manual-page figure.manual-shot { margin: 18px 0 20px; }
    .manual-page figure.manual-shot img {
        display: block;
        width: 100%;
        height: auto;
        border: 1px solid var(--manual-rule);
        border-radius: 8px;
    }
    .manual-page figure.manual-shot figcaption {
        font-size: 12px;
        color: var(--manual-faint);
        margin-top: 8px;
        padding-left: 12px;
        border-left: 2px solid var(--manual-rule);
    }

    /* tabel */
    .manual-page .manual-table-wrap { overflow-x: auto; margin-bottom: 16px; }
    .manual-page table.manual-table {
        width: 100%;
        min-width: 480px;
        border-collapse: collapse;
        font-size: 13px;
    }
    .manual-page table.manual-table th {
        text-align: left;
        font-size: 10px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--manual-faint);
        font-weight: 600;
        border-bottom: 1px solid #c3cecb;
        padding: 8px 12px 8px 0;
        white-space: nowrap;
    }
    .manual-page table.manual-table td {
        border-bottom: 1px solid var(--manual-rule);
        padding: 9px 12px 9px 0;
        vertical-align: top;
    }
    .manual-page table.manual-table td.manual-field {
        font-family: ui-monospace, Menlo, Consolas, monospace;
        font-size: 12px;
        white-space: nowrap;
    }

    /* catatan */
    .manual-page .manual-note,
    .manual-page .manual-warn {
        border-left: 3px solid var(--manual-accent);
        background: var(--manual-accent-soft);
        border-radius: 0 8px 8px 0;
        padding: 14px 18px;
        margin: 0 0 16px;
        font-size: 13px;
        max-width: 82ch;
    }
    .manual-page .manual-warn {
        border-left-color: var(--manual-alert);
        background: var(--manual-alert-soft);
    }
    .manual-page .manual-note b,
    .manual-page .manual-warn b { display: block; margin-bottom: 3px; }
    .manual-page .manual-note p:last-child,
    .manual-page .manual-warn p:last-child { margin-bottom: 0; }

    .manual-page .manual-pill {
        display: inline-block;
        font-size: 10px;
        letter-spacing: .04em;
        padding: 2px 9px;
        border-radius: 999px;
        border: 1px solid #c3cecb;
        color: var(--manual-soft);
        white-space: nowrap;
    }
    .manual-page .manual-pill.is-open { border-color: var(--manual-good); color: var(--manual-good); }
    .manual-page .manual-pill.is-locked { border-color: var(--manual-alert); color: var(--manual-alert); }

    .manual-page .manual-steps { counter-reset: manualstep; list-style: none; padding-left: 0; }
    .manual-page .manual-steps li {
        counter-increment: manualstep;
        position: relative;
        padding-left: 30px;
        margin-bottom: 10px;
    }
    .manual-page .manual-steps li::before {
        content: counter(manualstep);
        position: absolute;
        left: 0;
        top: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--manual-accent-soft);
        color: var(--manual-accent);
        font-size: 11px;
        font-weight: 700;
        line-height: 20px;
        text-align: center;
    }

    @media (max-width: 767.98px) {
        .manual-page .manual-hero,
        .manual-page .manual-card { padding: 20px; }
        .manual-page h1.manual-title { font-size: 24px; }
    }
</style>

<div class="row mx-2 mt-4 d-flex justify-content-center manual-page">
    <div class="col-12">

        <div class="manual-hero">
            <div class="manual-eyebrow">Manual Penggunaan</div>
            <h1 class="manual-title">Buku Panduan LIMS PT Pralon</h1>
            <p class="manual-standfirst">
                Panduan seluruh modul aplikasi, disusun mengikuti urutan kerja sebenarnya:
                atur hak akses dulu, siapkan data master, baru jalankan transaksi harian.
                Kalau Anda baru memakai aplikasi ini, baca berurutan dari atas.
            </p>
            <dl class="manual-roles">
                <div>
                    <dt>Admin / IT</dt>
                    <dd>Menyiapkan menu, role, user, alur approval, dan seluruh data master</dd>
                </div>
                <div>
                    <dt>Petugas QC</dt>
                    <dd>Menjalankan inspeksi material, barang jadi, resales goods, dan pengujian lab</dd>
                </div>
                <div>
                    <dt>Atasan / Approver</dt>
                    <dd>Menyetujui atau mengembalikan hasil inspeksi lewat Dashboard</dd>
                </div>
            </dl>
        </div>

        <div class="manual-toc">
            <h2>Daftar Isi</h2>
            <div class="manual-toc-grid">
                <div class="manual-toc-col">
                    <strong>Bagian 1 &mdash; Pengaturan</strong>
                    <a href="#manual-dashboard">Dashboard</a>
                    <a href="#manual-menus">Menus</a>
                    <a href="#manual-role">Role Permission</a>
                    <a href="#manual-user">User Access</a>
                    <a href="#manual-approval">Approval</a>
                </div>
                <div class="manual-toc-col">
                    <strong>Bagian 2 &mdash; Data Master</strong>
                    <a href="#manual-product">Master Product</a>
                    <a href="#manual-category">Category Machine</a>
                    <a href="#manual-machine">Master Machine</a>
                    <a href="#manual-material">Material</a>
                    <a href="#manual-resales-master">Resales Goods</a>
                    <a href="#manual-lab-master">Master Lab</a>
                </div>
                <div class="manual-toc-col">
                    <strong>Bagian 3 &mdash; Transaksi</strong>
                    <a href="#manual-trx-material">Inspection Material</a>
                    <a href="#manual-trx-goods">Inspection Goods</a>
                    <a href="#manual-trx-resales">Inspection Resales Goods</a>
                    <a href="#manual-trx-lab">LAB</a>
                </div>
                <div class="manual-toc-col">
                    <strong>Bagian 4 &mdash; Alur End-to-End</strong>
                    <a href="#manual-flow-konsep">Siklus hidup transaksi</a>
                    <a href="#manual-flow-resales">Resales Goods: awal&ndash;akhir</a>
                    <a href="#manual-flow-lab">LAB: awal&ndash;akhir</a>
                    <a href="#manual-flow-goods">Inspection Goods: awal&ndash;akhir</a>
                    <a href="#manual-flow-material">Material + alur Reject</a>
                </div>
                <div class="manual-toc-col">
                    <strong>Bagian 5 &mdash; Referensi</strong>
                    <a href="#manual-status">Status transaksi</a>
                    <a href="#manual-trouble">Kalau macet</a>
                </div>
            </div>
        </div>

        <div class="manual-card">
            <h3>Urutan kerja</h3>
            <p class="manual-path">Baca bagian ini dulu sebelum menyentuh menu mana pun</p>
            <p>
                Aplikasi ini bertingkat. Setiap lapisan bergantung pada lapisan di bawahnya, jadi
                melompati satu tahap membuat tahap berikutnya kosong atau tidak bisa dipilih.
            </p>
            <ol class="manual-steps">
                <li><b>Pengaturan</b> &mdash; menu, role, dan hak akses menentukan apa yang bisa dilihat tiap orang. Alur approval juga didaftarkan di sini, per lokasi dan per jenis inspeksi.</li>
                <li><b>Data master</b> &mdash; produk, mesin, material, dan standar pengujian. Semua pilihan yang muncul di form transaksi berasal dari sini.</li>
                <li><b>Transaksi</b> &mdash; inspeksi dan pengujian harian. Petugas mengisi hasil, lalu mengirimkannya untuk disetujui.</li>
                <li><b>Approval dan laporan</b> &mdash; atasan menyetujui lewat Dashboard, setelah itu laporan bisa dicetak.</li>
            </ol>
            <div class="manual-note">
                <b>Yang paling sering bikin bingung</b>
                <p>
                    Hampir semua halaman transaksi menyaring daftarnya berdasarkan rentang tanggal,
                    dan bawaannya hanya satu bulan terakhir. Kalau transaksi yang Anda cari tidak
                    muncul, mundurkan dulu tanggal awalnya sebelum menyimpulkan datanya hilang.
                </p>
            </div>
        </div>

        {{-- ============================ BAGIAN 1 ============================ --}}
        <div class="manual-part">Bagian 1 &mdash; Pengaturan</div>

        <div class="manual-card" id="manual-dashboard">
            <h3>Dashboard</h3>
            <p class="manual-path">Dashboard</p>
            <p>
                Halaman pertama setelah login. Isinya ringkasan aktivitas QC dan
                <b>Approval Inbox</b> di sebelah kanan &mdash; daftar transaksi yang menunggu
                persetujuan Anda. Klik ikon mata pada salah satu baris untuk membuka detail
                transaksinya dan memberi keputusan.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/dashboard.png') }}" alt="Halaman Dashboard dengan ringkasan jumlah inspeksi, Approval Inbox, progress per area, dan antrean inspeksi hari ini">
                <figcaption>Dashboard. Approval Inbox di kanan atas adalah pintu masuk approver.</figcaption>
            </figure>
            <div class="manual-warn">
                <b>Catatan jujur soal angka di Dashboard</b>
                <p>
                    Kartu ringkasan, progress bar, dan tabel antrean saat ini masih memakai
                    <b>data contoh</b>, bukan hitungan dari database &mdash; keterangan itu tertulis
                    di halamannya sendiri. Yang sudah benar-benar hidup adalah Approval Inbox.
                    Jangan memakai angka-angka tersebut untuk pelaporan.
                </p>
            </div>
        </div>

        <div class="manual-card" id="manual-menus">
            <h3>Menus</h3>
            <p class="manual-path">Setting &rarr; Menus</p>
            <p>
                Mengatur isi menu samping aplikasi. Terbagi dua daftar: <b>Menu List</b> untuk menu
                induk seperti Setting, Master, dan Transaction, serta <b>Sub List</b> untuk halaman
                di dalamnya. Tombol hijau bertanda tambah di pojok tiap daftar dipakai untuk
                menambah entri.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/setting-menus.png') }}" alt="Halaman Menus dengan dua tabel berdampingan: Menu List berisi menu induk dan Sub List berisi submenu beserta induknya">
                <figcaption>Menu induk di kiri, submenu beserta induknya di kanan.</figcaption>
            </figure>
            <p>
                Submenu wajib punya <b>Parent</b>. Menu berstatus <code>Inactive</code> hilang dari
                sidebar tetapi datanya tetap tersimpan, jadi ini cara aman menyembunyikan halaman
                tanpa menghapusnya.
            </p>
        </div>

        <div class="manual-card" id="manual-role">
            <h3>Role Permission</h3>
            <p class="manual-path">Setting &rarr; Role Permission</p>
            <p>
                Mendaftarkan <b>Role</b> (jabatan seperti Admin, Super Admin, User) dan
                <b>Permission</b> (izin satuan seperti <code>view-approval</code> atau
                <code>view-inspection-goods</code>). Halaman ini hanya membuat daftarnya;
                penempelan izin ke role dan role ke orang dilakukan di User Access.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/setting-role-permission.png') }}" alt="Halaman Role Permission dengan daftar Role di kiri dan daftar Permission di kanan">
                <figcaption>Daftar role dan daftar izin. Penamaan izin mengikuti pola view-&lt;nama halaman&gt;.</figcaption>
            </figure>
            <div class="manual-warn">
                <b>Hati-hati menghapus</b>
                <p>
                    Menghapus permission yang masih dipakai sebuah role akan mencabut akses semua
                    orang yang memegang role tersebut. Periksa dulu di User Access sebelum menekan
                    tombol merah.
                </p>
            </div>
        </div>

        <div class="manual-card" id="manual-user">
            <h3>User Access</h3>
            <p class="manual-path">Setting &rarr; User Access</p>
            <p>
                Tempat menyambungkan orang, role, dan izin. Daftar kiri berisi pengguna beserta
                role-nya; daftar kanan berisi role beserta tombol untuk menambah dan melihat izin
                yang menempel padanya.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/setting-user-access.png') }}" alt="Halaman User Access dengan tabel pengguna beserta rolenya di kiri, dan tabel role dengan tombol tambah izin di kanan">
                <figcaption>Pengguna dan role di kiri; pengelolaan izin per role di kanan.</figcaption>
            </figure>
            <p>
                Urutannya: buat role dan permission dulu di halaman sebelumnya, tempelkan
                permission ke role di sini, baru berikan role ke pengguna. Perubahan hak akses baru
                terasa setelah pengguna yang bersangkutan login ulang.
            </p>
        </div>

        <div class="manual-card" id="manual-approval">
            <h3>Approval</h3>
            <p class="manual-path">Setting &rarr; Approval</p>
            <p>
                Menentukan siapa menyetujui apa. Satu baris mewakili satu aturan approval, dan
                aturan dipisah berdasarkan <b>Location</b>, <b>Inspection Type</b> (Material,
                Goods, Resales, Lab), <b>Type</b>, serta <b>Shift</b>. Artinya Cimanggis bisa punya
                rantai persetujuan yang berbeda dari Kantor HO.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/setting-approval.png') }}" alt="Halaman Approval List dengan kolom Status, Inspection Code, Location, Inspection Type, Type, Shift, dan tombol pengaturan step">
                <figcaption>Daftar aturan approval. Tombol biru di kolom Action membuka pengaturan urutan penyetuju.</figcaption>
            </figure>
            <p>
                Tombol pada kolom Action membuka daftar <b>step</b>: urutan orang yang harus
                menyetujui, satu per satu. Transaksi baru berstatus selesai setelah step terakhir
                menyetujui.
            </p>
            <div class="manual-warn">
                <b>Kalau aturannya belum ada</b>
                <p>
                    Transaksi yang dikirim untuk disetujui tetapi belum punya aturan approval di
                    lokasinya akan menggantung tanpa penyetuju. Daftarkan aturannya lebih dulu.
                </p>
            </div>
        </div>

        {{-- ============================ BAGIAN 2 ============================ --}}
        <div class="manual-part">Bagian 2 &mdash; Data Master</div>

        <div class="manual-card" id="manual-product">
            <h3>Master Product</h3>
            <p class="manual-path">Master &rarr; Master Product</p>
            <p>
                Daftar produk pipa beserta ukuran standarnya. Ini master paling penting di
                aplikasi: nilai di sini dipakai sebagai acuan penilaian di modul Inspection Goods
                dan bisa ditarik otomatis oleh modul LAB.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-product.png') }}" alt="Halaman Master Product dengan kolom Seri, Join, Ukuran, Marking, Berat, Kode, Formula, dan blok OD Standart berisi Min, Toleransi, Marking, Avg, Max">
                <figcaption>Master Product. Blok OD Standart dan WT Standart di kanan berisi batas ukuran yang dipakai untuk menilai hasil inspeksi.</figcaption>
            </figure>
            <div class="manual-table-wrap">
                <table class="manual-table">
                    <thead><tr><th>Kelompok kolom</th><th>Isi</th></tr></thead>
                    <tbody>
                        <tr><td class="manual-field">Identitas</td><td>Seri, Join, Ukuran, Marking, Marking 2, Formula</td></tr>
                        <tr><td class="manual-field">Berat</td><td>Berat per pcs dan per meter</td></tr>
                        <tr><td class="manual-field">Kode</td><td>Kode TS dan kode BE</td></tr>
                        <tr><td class="manual-field">OD Standart</td><td>Min, Toleransi, Marking, Rata-rata, Maks untuk diameter luar</td></tr>
                        <tr><td class="manual-field">WT Standart</td><td>Min, Toleransi, Marking, Rata-rata, Maks untuk tebal dinding</td></tr>
                    </tbody>
                </table>
            </div>
            <p>
                Kolom <b>Marking</b> pada blok standar ditulis dengan gaya <code>63 + 0.3</code>,
                sama persis dengan cara menulisnya di formulir kertas. Selain tombol tambah, ada
                tombol unggah untuk memasukkan banyak produk sekaligus lewat berkas Excel.
            </p>
            <div class="manual-note">
                <b>Kaitan dengan modul LAB</b>
                <p>
                    Di Master Lab, sebuah parameter bisa disetel memakai <code>Product Field</code>
                    sehingga batas penilaiannya mengambil <code>min_od</code>, <code>max_od</code>,
                    <code>avg_od</code>, <code>min_wt</code>, <code>max_wt</code>, atau
                    <code>avg_wt</code> dari baris produk ini. Dengan begitu satu aturan berlaku
                    untuk semua ukuran pipa.
                </p>
            </div>
        </div>

        <div class="manual-card" id="manual-category">
            <h3>Category Machine</h3>
            <p class="manual-path">Master &rarr; Category Machine</p>
            <p>
                Pengelompokan mesin berdasarkan jenis bahan yang diolah, misalnya PVC, PE, dan PPR.
                Kategori harus dibuat lebih dulu karena setiap mesin wajib punya kategori.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-category-machine.png') }}" alt="Halaman Category List dengan kolom Name, Description, dan Action berisi tiga kategori PE, PPR, dan PVC">
                <figcaption>Kategori mesin. Cukup nama dan keterangan.</figcaption>
            </figure>
        </div>

        <div class="manual-card" id="manual-machine">
            <h3>Master Machine</h3>
            <p class="manual-path">Master &rarr; Master Machine</p>
            <p>
                Daftar mesin produksi. Tiap mesin punya <b>No Machine</b>, <b>Name</b>,
                <b>Category</b>, dan status aktif. Mesin inilah yang nanti dipilih saat membuat
                transaksi inspeksi dan pengujian lab.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-machine.png') }}" alt="Halaman Machine List dengan kolom Status, No Machine, Name, Category, dan Action">
                <figcaption>Daftar mesin beserta kategorinya.</figcaption>
            </figure>
            <div class="manual-note">
                <b>Mesin disaring per lokasi</b>
                <p>
                    Pada form transaksi, pilihan mesin mengikuti lokasi yang dipilih. Kalau
                    dropdown mesin kosong, kemungkinan lokasi tersebut memang belum punya mesin
                    terdaftar &mdash; bukan kesalahan aplikasi.
                </p>
            </div>
        </div>

        <div class="manual-card" id="manual-material">
            <h3>Material</h3>
            <p class="manual-path">Master &rarr; Material</p>
            <p>
                Daftar bahan baku beserta spesifikasinya, misalnya ASRENE, R 85, dan R 85 COKLAT.
                Dipakai sebagai pilihan pada modul Inspection Material dan sebagai keterangan
                formula pada inspeksi barang jadi.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-material.png') }}" alt="Halaman Material List dengan kolom Status, Material, Spec, dan Action">
                <figcaption>Daftar material. Status nonaktif menyembunyikannya dari pilihan transaksi.</figcaption>
            </figure>
        </div>

        <div class="manual-card" id="manual-resales-master">
            <h3>Master Product Resales Goods</h3>
            <p class="manual-path">Master &rarr; Resales Goods</p>
            <p>
                Daftar barang dagang &mdash; fitting, aksesoris, dan barang yang dijual kembali
                tanpa diproduksi sendiri. Berbeda dari Master Product, setiap barang di sini punya
                <b>Specification</b> berupa sekumpulan simbol dan nilai, misalnya
                <code>D: 432</code> atau <code>L: 231</code>, yang bisa ditambah sendiri sesuai
                kebutuhan barangnya.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-resales-goods.png') }}" alt="Halaman Master Product Resales Goods dengan kolom ID, Name, Size, Specification berupa label simbol, Created At, dan Action">
                <figcaption>Spesifikasi ditampilkan sebagai label. Tombol Add Specification di form memungkinkan menambah simbol baru.</figcaption>
            </figure>
            <p>
                Sama seperti Master Product, tersedia tombol unggah untuk memasukkan data massal
                dari berkas Excel.
            </p>
        </div>

        <div class="manual-card" id="manual-lab-master">
            <h3>Master Lab</h3>
            <p class="manual-path">Master &rarr; Master Lab</p>
            <p>
                Menyiapkan standar pengujian laboratorium. Ada tiga tab dan harus dikerjakan
                berurutan, karena tab berikutnya memerlukan isi tab sebelumnya.
            </p>

            <h4>Tab 1 &mdash; Test Method</h4>
            <p>
                Standar pengujian yang dipakai, misalnya SNI 06-0084-2002 untuk pipa uPVC. Isi
                <b>Code</b> (tercetak di laporan), <b>Name</b>, dan status.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-test-method.png') }}" alt="Tab Test Method pada Master Lab, menampilkan tabel metode uji dengan kolom Code, Name, Description, dan Status">
                <figcaption>Satu baris untuk satu standar pengujian.</figcaption>
            </figure>

            <h4>Tab 2 &mdash; Test Parameter</h4>
            <p>
                Butir yang diukur: Outside Diameter, Wall Thickness, Tensile Strength, dan
                seterusnya. Parameter dibuat sekali lalu dipakai ulang oleh metode mana pun.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-test-parameter.png') }}" alt="Tab Test Parameter menampilkan 13 parameter uji dengan kode, nama, tipe hasil, dan satuan bawaan">
                <figcaption>Tipe hasil menentukan bentuk isian yang nanti dilihat petugas lab.</figcaption>
            </figure>
            <div class="manual-table-wrap">
                <table class="manual-table">
                    <thead><tr><th>Field</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td class="manual-field">Code</td><td>Kode singkat, misal <code>OD</code>, <code>WT</code>, <code>TENS</code></td></tr>
                        <tr><td class="manual-field">Name</td><td>Nama yang tercetak di kolom ITEM pada laporan</td></tr>
                        <tr><td class="manual-field">Result Type</td><td><code>Numeric</code> untuk angka, <code>Text</code> untuk pengamatan, <code>Boolean</code> untuk ya/tidak. Tidak bisa diubah setelah dipakai transaksi.</td></tr>
                        <tr><td class="manual-field">Default Unit</td><td>Satuan bawaan seperti <code>mm</code> atau <code>kg/cm2</code></td></tr>
                        <tr><td class="manual-field">Description</td><td>Tercetak di kolom SPECIES pada laporan</td></tr>
                    </tbody>
                </table>
            </div>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-edit-parameter.png') }}" alt="Form Edit Test Parameter dengan isian Code, Name, Result Type, Default Unit, Description, dan Status">
                <figcaption>Isi Description dengan kalimat yang selama ini tertulis di kolom SPECIES formulir kertas.</figcaption>
            </figure>

            <h4>Tab 3 &mdash; Product Configuration</h4>
            <p>
                Merangkai metode dan parameter menjadi aturan nyata untuk satu produk. Pilih
                produk, pasang metodenya lewat <b>Assigned Test Methods</b> lalu
                <b>Save Assignment</b>. Setelah itu klik tombol konfigurasi pada metode yang
                terpasang, centang parameter yang dipakai, isi aturannya, dan tekan
                <b>Save Parameters</b>.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/master-product-configuration.png') }}" alt="Tab Product Configuration dengan panel Parameter Configuration menampilkan kartu parameter APP dan OD beserta isian aturan evaluasinya">
                <figcaption>Parameter tampil urut Sequence &mdash; sama dengan urutan di formulir cetak. Parameter yang tidak dicentang mengkerut jadi satu baris judul.</figcaption>
            </figure>
            <div class="manual-table-wrap">
                <table class="manual-table">
                    <thead><tr><th>Operator</th><th>Dinilai GOOD kalau</th><th>Tercetak sebagai</th></tr></thead>
                    <tbody>
                        <tr><td class="manual-field">Between</td><td>nilai ada di antara Min dan Max</td><td><code>60 + 0.4</code> bila ada toleransi, selain itu <code>0.6 ~ 0.7</code></td></tr>
                        <tr><td class="manual-field">&ge; Minimum</td><td>nilai &ge; Min</td><td><code>450 kg/cm2, Min</code></td></tr>
                        <tr><td class="manual-field">&le; Maximum</td><td>nilai &le; Max</td><td><code>5 %, Max</code></td></tr>
                        <tr><td class="manual-field">Equal / Target</td><td>nilai sama dengan Target</td><td><code>60</code></td></tr>
                    </tbody>
                </table>
            </div>
            <p>
                Pilih <code>Static</code> kalau batasnya sama untuk semua ukuran, dan
                <code>Product Field</code> kalau batasnya mengikuti ukuran pipa di Master Product.
                <b>Measurement Count</b> menentukan berapa kotak angka yang nanti diisi petugas,
                dan <b>Calculation</b> menentukan cara meringkasnya menjadi satu nilai.
            </p>
        </div>

        {{-- ============================ BAGIAN 3 ============================ --}}
        <div class="manual-part">Bagian 3 &mdash; Transaksi</div>

        <div class="manual-card" id="manual-trx-material">
            <h3>Inspection Material</h3>
            <p class="manual-path">Transaction &rarr; Inspection Material</p>
            <p>
                Pemeriksaan bahan baku yang datang: resin dan pellet. Daftar menampilkan tanggal,
                lokasi, kode inspeksi, produk, tipe bahan, dan status.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/trx-inspection-material.png') }}" alt="Halaman Inspection Material Record dengan kolom Created at, Location, Inspection Code, Product, Type, Status, dan tiga tombol aksi per baris">
                <figcaption>Tiga tombol aksi per baris: lihat detail, log approval, dan cetak.</figcaption>
            </figure>
            <p>
                Tombol hijau bertanda tambah membuka form header inspeksi. Setelah header dibuat,
                isi hasil pemeriksaannya lewat form yang sesuai jenis bahan:
            </p>
            <ul>
                <li><b>Resin Inspection</b> &mdash; informasi material, Bulk Density, dan Inspection Properties.</li>
                <li><b>Pellet Inspection</b> &mdash; informasi material, hasil pemeriksaan, dan catatan.</li>
            </ul>
            <p>
                Tombol bergambar orang membuka <b>Log Approval</b> untuk melihat siapa saja yang
                sudah menyetujui, dan tombol printer mencetak laporannya.
            </p>
        </div>

        <div class="manual-card" id="manual-trx-goods">
            <h3>Inspection Goods</h3>
            <p class="manual-path">Transaction &rarr; Inspection Goods</p>
            <p>
                Pemeriksaan barang jadi hasil produksi sendiri. Ini modul dengan isian paling
                rinci, karena satu transaksi bisa berisi banyak pemeriksaan per shift dan per
                mesin.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/trx-inspection-goods.png') }}" alt="Halaman Inspection Goods Record dengan kolom Created at, Inspection Code, Location, dan Status berisi enam transaksi">
                <figcaption>Setiap baris bisa dibuka untuk melihat detail pemeriksaan di dalamnya.</figcaption>
            </figure>
            <p>Susunan datanya bertingkat tiga:</p>
            <ol class="manual-steps">
                <li><b>Header</b> &mdash; lokasi dan kode inspeksi.</li>
                <li><b>Detail</b> &mdash; pembagian per shift.</li>
                <li><b>Sub detail</b> &mdash; satu baris pemeriksaan: inspektur, mesin, produk, kode manufaktur, panjang pipa, penampilan, marking, kondisi potongan, sudut spigot, warna, material, dan diameter.</li>
            </ol>
            <p>
                Tersedia form khusus <b>Wall Thickness</b> untuk mencatat tebal dinding per titik,
                serta <b>History Progress</b> dan <b>Report Progress</b> untuk melihat dan
                mengekspor rekap pemeriksaan.
            </p>
            <div class="manual-note">
                <b>Filter tanggal sudah diperbaiki</b>
                <p>
                    Sebelumnya tombol Filter di halaman ini selalu memakai rentang tanggal saat
                    halaman pertama dibuka, sehingga mengubah tanggal tidak berpengaruh dan daftar
                    tampak kosong padahal datanya ada. Sekarang tombol Filter membaca tanggal yang
                    sedang tampil di layar.
                </p>
            </div>
        </div>

        <div class="manual-card" id="manual-trx-resales">
            <h3>Inspection Resales Goods</h3>
            <p class="manual-path">Transaction &rarr; Inspection Resales Goods</p>
            <p>
                Pemeriksaan barang dagang. Satu transaksi mewakili satu penerimaan, ditandai
                <b>No. PF</b> dan keterangan, lalu diisi baris-baris detail barang yang diperiksa.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/trx-resales-goods.png') }}" alt="Halaman Resales Goods Inspection dengan satu transaksi berstatus Approval Progress beserta tombol detail, tambah, cetak, dan hapus">
                <figcaption>Status Approval Progress berarti transaksi sudah dikunci dan sedang menunggu persetujuan.</figcaption>
            </figure>
            <p>
                Saat menambah detail, spesifikasi barang diambil dari Master Resales Goods, dan
                tersedia tombol <b>Add Custom</b> untuk menambahkan simbol pemeriksaan yang tidak
                ada di master.
            </p>
            <p>
                Setelah seluruh detail terisi, transaksi dikunci lewat <b>Confirm Lock</b> sebelum
                masuk antrean approval. Laporannya bisa diunduh sebagai PDF atau Excel lewat tombol
                cetak.
            </p>
        </div>

        <div class="manual-card" id="manual-trx-lab">
            <h3>LAB</h3>
            <p class="manual-path">Transaction &rarr; LAB</p>
            <p>
                Pengujian laboratorium terhadap produk, memakai aturan yang sudah disiapkan di
                Master Lab. Hasil akhirnya adalah formulir
                <b>Record of Laboratory Testing</b> (03/QC/12-1999 Rev-00).
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/lab-index.png') }}" alt="Halaman Product Laboratory menampilkan dua transaksi, satu berstatus In Progress dan satu Done dengan tombol export Excel dan PDF">
                <figcaption>Tombol Excel dan PDF hanya muncul pada baris yang sudah sampai Approval atau Done.</figcaption>
            </figure>

            <h4>Membuat transaksi</h4>
            <p>
                Klik <b>New Test</b>. Pilih produk lebih dulu, karena pilihan metode menyesuaikan
                produk. Isi tanggal uji, nomor laporan, shift, kode produksi, lot material, lokasi,
                mesin, serta kondisi ruang: suhu, kelembapan, dan jenis produk.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/lab-new-test.png') }}" alt="Modal Create Product Laboratory Test dengan isian produk, metode, tanggal, shift, kode produksi, lot material, lokasi, mesin, suhu, kelembapan, dan jenis produk">
                <figcaption>Begitu disimpan, seluruh baris parameter terbit otomatis sesuai konfigurasi produk dan metodenya.</figcaption>
            </figure>

            <h4>Mengisi hasil</h4>
            <p>
                Klik ikon mata untuk membuka detail. Daftar parameter ada di kiri dengan titik
                penanda: hijau berarti GOOD, merah NO GOOD, abu-abu belum dinilai. Panel kanan
                menampilkan satu parameter saja.
            </p>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/lab-detail.png') }}" alt="Modal detail transaksi lab dengan daftar parameter bertitik warna di kiri dan panel isian delapan titik pengukuran Outside Diameter di kanan">
                <figcaption>Isi satu parameter, tekan Save Result, lalu pindah ke berikutnya. Setiap parameter tersimpan sendiri-sendiri.</figcaption>
            </figure>
            <p>
                Setelah disimpan, sistem langsung menghitung dan menilai: titik warna berubah dan
                penghitung <code>x / 13 EVALUATED</code> naik, tanpa memindahkan Anda dari parameter
                yang sedang dikerjakan. Item <b>Comment</b> di bawah daftar parameter mengisi bagian
                IV COMMENT pada formulir cetak.
            </p>
            <p>
                Kalau seluruh parameter wajib sudah terisi, tekan <b>Submit for Approval</b>.
                Transaksi terkunci dan masuk antrean persetujuan.
            </p>

            <h4>Mencetak laporan</h4>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/report-preview.png') }}" alt="Hasil cetak Record of Laboratory Testing dengan kondisi pengujian, tabel 13 parameter beserta hasil ukur dan evaluasi, kolom komentar, dan blok tanda tangan">
                <figcaption>PDF dan Excel memakai tata letak yang sama, mengikuti formulir 03/QC/12-1999 Rev-00.</figcaption>
            </figure>
            <div class="manual-table-wrap">
                <table class="manual-table">
                    <thead><tr><th>Bagian formulir</th><th>Sumber data</th></tr></thead>
                    <tbody>
                        <tr><td class="manual-field">REPORT NO</td><td>Field Report No. saat membuat transaksi</td></tr>
                        <tr><td class="manual-field">Date OF Tested</td><td>Test Date, ditulis dalam bahasa Indonesia</td></tr>
                        <tr><td class="manual-field">Shift / Temp. / Humd.</td><td>Shift dalam angka Romawi, Temperature, Humidity</td></tr>
                        <tr><td class="manual-field">Kolom ITEM</td><td>Nama parameter di Master Lab</td></tr>
                        <tr><td class="manual-field">Kolom SPECIES</td><td>Description parameter di Master Lab</td></tr>
                        <tr><td class="manual-field">Kolom SPECIFICATION</td><td>Dirakit dari Operator, batas, dan toleransi</td></tr>
                        <tr><td class="manual-field">Kolom FIGURE MEASURED</td><td>Titik-titik pengukuran dan hasil hitungannya</td></tr>
                        <tr><td class="manual-field">Kolom EVALUATION</td><td>Penilaian sistem: Good atau No Good</td></tr>
                        <tr><td class="manual-field">IV COMMENT</td><td>Item Comment di modal detail</td></tr>
                        <tr><td class="manual-field">Blok tanda tangan</td><td>Nama pengaju dan approver yang sudah menyetujui</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============================ BAGIAN 4 ============================ --}}
        {{-- ============================ BAGIAN 4 ============================ --}}
        <div class="manual-part">Bagian 4 &mdash; Alur End-to-End (Bergambar)</div>

        <div class="manual-card" id="manual-flow-konsep">
            <h3>Siklus hidup transaksi</h3>
            <p class="manual-path">Berlaku sama untuk keempat modul transaksi</p>
            <p>
                Semua transaksi inspeksi mengikuti siklus yang sama. Yang berbeda hanya objek
                yang diperiksa dan jumlah step approver (diatur per lokasi di Setting &rarr; Approval).
            </p>
            <ol class="manual-steps">
                <li><b>Draft &rarr; In Progress</b> &mdash; petugas membuat transaksi lalu mengisi hasil inspeksi.</li>
                <li><b>Lock</b> &mdash; petugas mengunci transaksi. Sejak titik ini data <b>tidak bisa diubah</b> &mdash; tombol edit hilang dan server pun menolak perubahan.</li>
                <li><b>Approval Progress</b> &mdash; approver step 1 menerima giliran di Approval Inbox Dashboard; setiap persetujuan meneruskan ke step berikutnya.</li>
                <li><b>Done</b> &mdash; semua step setuju; report PDF/Excel bisa dicetak.</li>
                <li><b>Reject</b> &mdash; kalau approver menolak, transaksi <b>terbuka kembali</b> untuk diperbaiki (Material &rarr; <i>Rejected</i>, Goods &rarr; shift kembali <i>Incomplete</i>, LAB &rarr; <i>Revision</i>, Resales &rarr; <i>In Progress</i>), lalu di-lock ulang dan approval dimulai dari step 1 lagi.</li>
            </ol>
            <div class="manual-note">
                <b>Semua keputusan tercatat</b>
                <p>Setiap approve/reject tersimpan di riwayat approval beserta nama, waktu, dan remark &mdash; terlihat oleh pembuat transaksi maupun approver lain.</p>
            </div>
        </div>

        <div class="manual-card" id="manual-flow-resales">
            <h3>Resales Goods: dari master sampai report</h3>
            <p class="manual-path">Master &rarr; Resales Goods &nbsp;&middot;&nbsp; Transaction &rarr; Inspection Resales Goods &nbsp;&middot;&nbsp; Dashboard</p>
            <ol class="manual-steps">
                <li>Siapkan master product beserta nilai standard tiap simbol (D, d, L, Z, H&hellip;).</li>
                <li>Buat transaksi: pilih lokasi, product, inspector, supplier/produsen, tipe, quantity.</li>
                <li>Isi sample &mdash; kolom standard terisi otomatis dari master, petugas mengisi actual value.</li>
                <li>Lock dengan No. PF &rarr; masuk Approval Inbox approver step 1.</li>
                <li>Approver menyetujui step demi step &rarr; status <b>DONE</b> &rarr; cetak PDF/Excel.</li>
            </ol>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r02-master-add.jpg') }}" alt="Modal tambah master product resales goods dengan spesifikasi per simbol" loading="lazy">
                <figcaption>Langkah 1 &mdash; master product baru: nama, size, dan standard per simbol. Nilai inilah yang jadi pembanding saat pengisian sample.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r03-add-header.jpg') }}" alt="Modal tambah transaksi resales goods" loading="lazy">
                <figcaption>Langkah 2 &mdash; header transaksi. Kode <code>N/INSRG/BULAN/TAHUN</code> dibuat otomatis.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r04-detail-spec.jpg') }}" alt="Pengisian actual value sample terhadap standard master" loading="lazy">
                <figcaption>Langkah 3 &mdash; isi sample. Kolom hijau = standard dari master; kolom kanan diisi hasil ukur. Simbol custom bisa ditambah bila perlu.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r05-lock.jpg') }}" alt="Modal lock transaksi resales goods dengan nomor PF" loading="lazy">
                <figcaption>Langkah 4 &mdash; lock. No. PF wajib; setelah ini transaksi read-only.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r06-approval.jpg') }}" alt="Modal approval resales goods di dashboard" loading="lazy">
                <figcaption>Langkah 5 &mdash; approver membuka detail dari Approval Inbox, lalu memilih Approve / Return for Revision + remark.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/r07-done-print.jpg') }}" alt="Status done dan pilihan cetak PDF atau Excel" loading="lazy">
                <figcaption>Selesai &mdash; status DONE, report bisa diunduh sebagai PDF atau Excel.</figcaption>
            </figure>
        </div>

        <div class="manual-card" id="manual-flow-lab">
            <h3>LAB: dari metode uji sampai report</h3>
            <p class="manual-path">Master &rarr; Master Lab &nbsp;&middot;&nbsp; Transaction &rarr; LAB &nbsp;&middot;&nbsp; Dashboard</p>
            <ol class="manual-steps">
                <li>Siapkan master: Test Method &rarr; Test Parameter &rarr; Product Configuration (metode + parameter + aturan evaluasi per product).</li>
                <li>Buat transaksi: pilih product + metode; parameter uji tergenerate otomatis dari konfigurasi.</li>
                <li>Isi titik ukur per parameter &mdash; sistem menghitung hasil dan memberi vonis GOOD / NO GOOD.</li>
                <li>Submit &rarr; approval 2 step &rarr; <b>Done</b> &rarr; cetak form &ldquo;Record of Laboratory Testing&rdquo;.</li>
            </ol>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/l02-product-config.jpg') }}" alt="Konfigurasi parameter uji per product" loading="lazy">
                <figcaption>Langkah 1 &mdash; Product Configuration: operator (BETWEEN/&ge;/&le;), jumlah titik ukur, kalkulasi, dan wajib/opsional per parameter. Konfigurasi di-<b>snapshot</b> ke tiap transaksi.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/l03-add-trx.jpg') }}" alt="Modal pengujian lab baru" loading="lazy">
                <figcaption>Langkah 2 &mdash; New Test: product, metode, tanggal uji, shift, suhu/kelembapan.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/l04-fill-result.jpg') }}" alt="Pengisian hasil ukur parameter lab" loading="lazy">
                <figcaption>Langkah 3 &mdash; isi hasil per parameter: titik ukur, In Charge &amp; Checker, lalu Save Result. Titik hijau di panel kiri = sudah dievaluasi.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/l06-approval.jpg') }}" alt="Modal approval laboratorium di dashboard" loading="lazy">
                <figcaption>Langkah 4 &mdash; approver melihat seluruh hasil + evaluasi sebelum memutuskan. Revisi mengembalikan transaksi untuk diperbaiki.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/l07-done-history.jpg') }}" alt="Detail lab berstatus done dengan riwayat approval" loading="lazy">
                <figcaption>Selesai &mdash; riwayat approval per step tercatat; tombol PDF/Excel menghasilkan report standar.</figcaption>
            </figure>
        </div>

        <div class="manual-card" id="manual-flow-goods">
            <h3>Inspection Goods: alur per shift</h3>
            <p class="manual-path">Transaction &rarr; Inspection Goods &nbsp;&middot;&nbsp; Dashboard</p>
            <p>
                Satu header otomatis berisi <b>3 shift</b>; tiap shift diisi, di-lock, dan di-approve
                terpisah. Header menjadi <b>DONE</b> setelah ketiga shift selesai.
            </p>
            <ol class="manual-steps">
                <li>Buat header (cukup pilih lokasi) &mdash; 3 shift tercipta sekaligus.</li>
                <li>Buka baris header untuk melihat shift; tambah progress inspeksi di tiap shift.</li>
                <li>Lock shift &rarr; approver memeriksa item + wall thickness &rarr; approve 3 step &rarr; shift <b>Complete</b>.</li>
            </ol>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/g03-shifts.jpg') }}" alt="Tiga baris shift di dalam satu header inspection goods" loading="lazy">
                <figcaption>Langkah 1 &mdash; baris shift di dalam header, masing-masing dengan aksi tambah progress, edit, detail, history, update WT, dan lock.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/g04-progress-form.jpg') }}" alt="Form progress tab inspection item" loading="lazy">
                <figcaption>Langkah 2a &mdash; tab Inspection Item: inspector, mesin, product, panjang pipa, kondisi visual (&Omicron; / &Delta; / X).</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/g04b-dimension.jpg') }}" alt="Form progress tab dimension dengan 8 titik wall thickness" loading="lazy">
                <figcaption>Langkah 2b &mdash; tab Dimension: material, tape diameter, 8 titik wall thickness, evaluation spec. Setiap perubahan tercatat ke log history.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/g06-approval.jpg') }}" alt="Modal approval inspection goods per shift" loading="lazy">
                <figcaption>Langkah 3 &mdash; approval per shift: approver memeriksa tabel wall thickness lalu Approve/Reject. Reject mengembalikan shift ke Incomplete.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/g07-complete.jpg') }}" alt="Shift complete setelah approval penuh" loading="lazy">
                <figcaption>Selesai &mdash; shift Complete; tombol approval-log &amp; print aktif. Tiga shift Complete = header DONE.</figcaption>
            </figure>
        </div>

        <div class="manual-card" id="manual-flow-material">
            <h3>Inspection Material: termasuk alur Reject</h3>
            <p class="manual-path">Transaction &rarr; Inspection Material &nbsp;&middot;&nbsp; Dashboard</p>
            <p>
                Tipe <b>Resin</b> mengukur bulk density per step (jumlah step mengikuti jumlah sak:
                &le;50 &rarr; 6, 51&ndash;100 &rarr; 8, 101&ndash;150 &rarr; 13, &ge;151 &rarr; 20) plus properti visual;
                tipe <b>Pellet HDPE</b> mengisi properti + MFR. Contoh di bawah sekaligus menunjukkan
                apa yang terjadi saat approver <b>menolak</b>.
            </p>
            <ol class="manual-steps">
                <li>Buat transaksi: tipe, material, supplier, jumlah sak &amp; berat, sertifikat, no. lot.</li>
                <li>Isi tab Bulk Density per step dan tab Density Properties &rarr; status In Progress &rarr; lock.</li>
                <li>Approver menolak &rarr; status <b>Rejected</b>, transaksi terbuka kembali.</li>
                <li>Perbaiki nilai yang dipersoalkan &rarr; lock ulang &rarr; approve semua step &rarr; <b>DONE</b>.</li>
            </ol>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/m03a-bulk-density.jpg') }}" alt="Tab bulk density resin dengan grafik" loading="lazy">
                <figcaption>Langkah 2 &mdash; Bulk Density: nilai per step; min/max/avg dan grafik ter-update langsung.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/m04-approval-reject.jpg') }}" alt="Approver memilih reject dengan alasan" loading="lazy">
                <figcaption>Langkah 3 &mdash; approver memilih <b>Reject</b> + alasan dari Approval Inbox.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/m05-rejected.jpg') }}" alt="Status rejected dengan tombol edit dan lock kembali aktif" loading="lazy">
                <figcaption>Langkah 4 &mdash; status <b>Rejected</b>: tombol edit &amp; lock muncul lagi. Perbaiki datanya, lock ulang, approval mulai dari step 1.</figcaption>
            </figure>
            <figure class="manual-shot">
                <img src="{{ asset('manual/images/flow/m06-done.jpg') }}" alt="Status done inspection material" loading="lazy">
                <figcaption>Selesai &mdash; seluruh step menyetujui, status DONE, report siap dicetak.</figcaption>
            </figure>
            <div class="manual-warn">
                <b>Alasan penolakan tidak hilang</b>
                <p>Remark reject tetap tersimpan di riwayat approval walau transaksi sudah diperbaiki dan disetujui &mdash; jejak auditnya utuh.</p>
            </div>
        </div>

        {{-- ============================ BAGIAN 5 ============================ --}}
        <div class="manual-part">Bagian 5 &mdash; Referensi</div>

        <div class="manual-card" id="manual-status">
            <h3>Status transaksi</h3>
            <p>
                Penamaan status sedikit berbeda antar modul, tetapi maknanya sama: sebelum dikirim
                masih bisa diubah, sesudah dikirim terkunci.
            </p>
            <div class="manual-table-wrap">
                <table class="manual-table">
                    <thead><tr><th>Status</th><th>Arti</th><th>Masih bisa diubah</th></tr></thead>
                    <tbody>
                        <tr><td><span class="manual-pill is-open">Draft / New</span></td><td>Baru dibuat, belum ada hasil tersimpan</td><td>ya</td></tr>
                        <tr><td><span class="manual-pill is-open">In Progress</span></td><td>Sebagian hasil sudah disimpan</td><td>ya</td></tr>
                        <tr><td><span class="manual-pill is-locked">Approval Progress</span></td><td>Sudah dikirim, menunggu persetujuan</td><td>tidak</td></tr>
                        <tr><td><span class="manual-pill is-locked">Done</span></td><td>Seluruh step approval selesai</td><td>tidak</td></tr>
                        <tr><td><span class="manual-pill is-open">Revision</span></td><td>Dikembalikan approver untuk diperbaiki</td><td>ya</td></tr>
                    </tbody>
                </table>
            </div>
            <p>Laporan hanya bisa dicetak setelah transaksi minimal masuk tahap approval.</p>
        </div>

        <div class="manual-card" id="manual-trouble">
            <h3>Kalau macet</h3>

            <h4>Transaksi tidak muncul di daftar</h4>
            <p>
                Periksa rentang tanggalnya. Bawaannya hanya satu bulan terakhir, dan yang disaring
                adalah tanggal transaksi, bukan tanggal Anda membukanya. Mundurkan tanggal awal
                lalu tekan tombol filter.
            </p>

            <h4>Dropdown kosong saat mengisi form</h4>
            <p>
                Data masternya belum ada atau statusnya nonaktif. Mesin disaring per lokasi,
                metode uji disaring per produk, dan material nonaktif tidak ikut ditampilkan.
            </p>

            <h4>Menu tidak kelihatan di sidebar</h4>
            <p>
                Bisa karena menunya berstatus Inactive di Setting &rarr; Menus, atau role Anda
                belum punya izin halaman tersebut di Setting &rarr; User Access. Perubahan izin
                baru terasa setelah login ulang.
            </p>

            <h4>Transaksi sudah dikirim tetapi tidak ada yang bisa menyetujui</h4>
            <p>
                Aturan approval untuk lokasi dan jenis inspeksi tersebut belum didaftarkan di
                Setting &rarr; Approval, atau step-nya belum diisi orang.
            </p>

            <h4>Hasil lab dinilai NO GOOD padahal menurut petugas bagus</h4>
            <p>
                Periksa Operator, batas Min dan Max, serta Calculation di Master Lab &rarr; Product
                Configuration. Perlu diingat, transaksi memakai aturan yang berlaku <i>saat
                transaksi dibuat</i>; mengubah master sekarang tidak mengubah transaksi lama.
            </p>

            <h4>Kolom SPECIES kosong di laporan lab</h4>
            <p>Description parameter di Master Lab &rarr; Test Parameter belum diisi.</p>

            <h4>Tombol cetak tidak kelihatan</h4>
            <p>Transaksi belum sampai tahap approval. Selesaikan pengisian lalu kirim untuk disetujui.</p>
        </div>

        <p class="text-muted" style="font-size:12px; margin: 22px 0 40px;">
            Buku panduan LIMS PT Pralon &middot; halaman ini bisa dicetak langsung dari browser
            (Ctrl/Cmd + P).
        </p>

    </div>
</div>
@endsection
