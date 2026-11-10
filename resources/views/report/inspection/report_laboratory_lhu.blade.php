@php
    /**
     * LHU — "Laporan Pengujian Pipa PVC". Format mengikuti dokumen resmi QC
     * (contoh: 05367/QC-LAB/XI/2023): kop surat beralamat, keterangan contoh,
     * metode uji, tabel hasil uji 4 kolom, kesimpulan, dan tiga penandatangan.
     * Dirender mpdf untuk printLaboratoryPdf.
     */
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $testDate = $header->test_date
        ? $header->test_date->format('d') . ' ' . $months[(int) $header->test_date->format('n')] . ' ' . $header->test_date->format('Y')
        : '-';

    $product = $header->productRelation;
    $productSpec = $product ? trim($product->seri . ' ' . $product->ukuran . ' ' . $product->marking) : '';
    $identity = trim('Pipa PVC Merk Pralon ' . $productSpec) ?: '-';

    $methodCode = optional(optional($header->productMethodRelation)->methodRelation)->code ?: data_get($header->method_snapshot, 'code');
    $methodName = optional(optional($header->productMethodRelation)->methodRelation)->name ?: data_get($header->method_snapshot, 'name');

    $sampleName = $header->sample_name ?: ($methodName ?: '-');

    // tiga penandatangan: dua approver pertama (urutan step) + pembuat transaksi
    $approverRows = $history->where('status', 1)->values();
    $signers = [
        ['title' => 'FACTORY MANAGER', 'user' => optional($approverRows->get(0))->userRelation],
        ['title' => 'KABAG.QUALITY CONTROL', 'user' => optional($approverRows->get(1))->userRelation],
        ['title' => 'PENGUJIAN/LABORATORIUM', 'user' => $header->userRelation],
    ];

    $allGood = $rows->isNotEmpty() && $rows->every(function ($row) {
        return $row['evaluation'] === 'GOOD';
    });

    $fmt = function ($value) {
        return rtrim(rtrim(number_format((float) $value, 4, ',', '.'), '0'), ',');
    };
@endphp

<style>
    body { font-family: sans-serif; font-size: 9.5pt; color: #000; }
    table { border-collapse: collapse; }
    .kop td { vertical-align: middle; }
    .kop-address { font-size: 7.2pt; line-height: 1.3; }
    .kop-address td { vertical-align: top; padding-bottom: 4px; }
    .kop-address .kop-label { width: 52px; white-space: nowrap; }
    .kop-address .kop-colon { width: 7px; }
    .rule-red { border-bottom: 2.5px solid #e11d2e; margin: 6px 0 12px; }
    .title { text-align: center; font-size: 11.5pt; font-weight: bold; margin: 2px 0 12px; }
    .meta td { padding: 1px 0; font-size: 9.5pt; }
    .section-title { font-weight: bold; font-size: 10pt; padding-top: 10px; }
    .kv td { padding: 1.5px 0; }
    .hasil { width: 100%; margin-top: 4px; }
    .hasil th, .hasil td { border: 1px solid #000; padding: 6px 8px; font-size: 9pt; vertical-align: top; }
    .hasil th { text-align: center; font-weight: bold; }
    /* tabel dalam-sel: baris label dan baris nilai dibuat identik supaya
       Terbesar/Terkecil/Rata-rata sejajar persis dengan angkanya */
    .hasil table.lines { width: 100%; border-collapse: collapse; }
    .hasil table.lines td { border: 0; padding: 0 0 2px; font-size: 9pt; line-height: 1.35; vertical-align: top; }
    .hasil .sub { padding-left: 14px; }
    .center { text-align: center; }
    .sig { width: 100%; margin-top: 26px; }
    .sig td { width: 33.33%; text-align: center; vertical-align: bottom; }
    .sig .sig-title { font-weight: bold; font-size: 9.5pt; }
    .sig .sig-name { padding-top: 6px; font-size: 9.5pt; }
    .nowrap { white-space: nowrap; }
</style>

{{-- ===== KOP SURAT (dilewati saat cetak di kertas kop pracetak) ===== --}}
@if ($letterhead ?? true)
<table class="kop" style="width:100%">
    <tr>
        <td style="width:36%;">
            @if (!empty($logo))
                <img src="{{ $logo }}" style="width:165px">
            @else
                <b style="font-size:16pt; color:#e11d2e;">PRALON</b>
            @endif
        </td>
        <td style="width:64%">
            <table class="kop-address" style="width:100%">
                <tr>
                    <td class="kop-label"><b>Head&nbsp;Office</b></td>
                    <td class="kop-colon">:</td>
                    <td>Synergy Building #08-08, Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 - Indonesia<br>Phone : +6221 304 38808&nbsp;&nbsp;Fax : +6221 304 38801</td>
                </tr>
                <tr>
                    <td class="kop-label"><b>Factory&nbsp;1</b></td>
                    <td class="kop-colon">:</td>
                    <td>Jl. Raya Bogor KM. 32.5, Cimanggis, Depok - Jawa Barat<br>Phone : (021) 874 1028 (Hunting)&nbsp;&nbsp;Fax : (021) 874 0913</td>
                </tr>
                <tr>
                    <td class="kop-label"><b>Factory&nbsp;2</b></td>
                    <td class="kop-colon">:</td>
                    <td>Dusun Gintung Kebon RT 12&amp;13 RW 03 Desa Gintung Kerta, Kec. Klari - 41371, Kab. Karawang, Jawa Barat - Indonesia<br>Phone : (0267) 861 7238/88 (Hunting)&nbsp;&nbsp;Fax : (0267) 861 7228</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div class="rule-red"></div>
@endif

<div class="title">LAPORAN PENGUJIAN PIPA PVC</div>

{{-- ===== META ===== --}}
<table class="meta">
    <tr><td style="width:150px">Nomor Pengujian</td><td style="width:10px">:</td><td><b>{{ $header->report_no ?: $header->laboratory_code }}</b></td></tr>
    <tr><td>Nama contoh Uji</td><td>:</td><td>{{ $sampleName }}</td></tr>
    <tr><td>Tanggal Pengujian</td><td>:</td><td>{{ $testDate }}</td></tr>
</table>

{{-- ===== I. KETERANGAN CONTOH ===== --}}
<div class="section-title">I.&nbsp;&nbsp;Keterangan Contoh</div>
<table class="kv" style="width:100%; margin-left:14px;">
    <tr><td style="width:230px">1.&nbsp; Identitas Contoh</td><td style="width:10px">:</td><td><b>{{ $identity }}</b></td></tr>
    <tr><td>2.&nbsp; Keadaan Contoh</td><td>:</td><td>{{ $header->sample_condition ?: '-' }}</td></tr>
    <tr><td>3.&nbsp; Petunjuk Pengambilan Contoh</td><td>:</td><td>{{ $header->sample_instruction ?: '-' }}</td></tr>
    @if ($header->customer_name || $header->project_name || $header->made_for)
        @php $no = 4; @endphp
        @if ($header->customer_name)<tr><td>{{ $no++ }}.&nbsp; Customer</td><td>:</td><td>{{ $header->customer_name }}</td></tr>@endif
        @if ($header->project_name)<tr><td>{{ $no++ }}.&nbsp; Proyek</td><td>:</td><td>{{ $header->project_name }}</td></tr>@endif
        @if ($header->made_for)<tr><td>{{ $no++ }}.&nbsp; Dibuat Untuk</td><td>:</td><td>{{ $header->made_for }}</td></tr>@endif
    @endif
</table>

{{-- ===== II. KETERANGAN PENGUJIAN ===== --}}
<div class="section-title">II.&nbsp;&nbsp;Keterangan Pengujian</div>
<table class="kv" style="width:100%; margin-left:14px;">
    <tr><td style="width:230px">1.&nbsp; Metode Uji</td><td style="width:10px">:</td><td><b>{{ $methodCode ?: '-' }}</b>{{ $methodName ? ', ' . $methodName : '' }}</td></tr>
</table>

{{-- ===== III. HASIL UJI ===== --}}
<div class="section-title">III.&nbsp;&nbsp;Hasil Uji</div>
<table class="hasil">
    <thead>
        <tr>
            <th style="width:34%">Uraian Pengujian</th>
            <th style="width:15%">Hasil Uji</th>
            <th style="width:37%">Syarat {{ $methodCode ?: '' }}</th>
            <th style="width:14%">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            @php
                $multi = count($row['measurements']) > 1;
                $values = collect($row['measurements'])->map(fn ($value) => (float) $value);
                $unitText = $row['unit'] ? ' (' . $row['unit'] . ')' : '';
                // specification dari formatReportStandard sudah menyertakan satuan
                $syarat = trim(($row['species'] ? $row['species'] : '') . ($row['specification'] !== '-' && $row['specification'] !== '' ? "\n" . $row['specification'] : ''));
            @endphp
            <tr>
                @if ($multi)
                    <td>
                        <table class="lines">
                            <tr><td>{{ $row['sequence'] }}.&nbsp; {{ $row['item'] }}</td></tr>
                            <tr><td class="sub">&#8227; Terbesar{{ $unitText }}</td></tr>
                            <tr><td class="sub">&#8227; Terkecil{{ $unitText }}</td></tr>
                            <tr><td class="sub">&#8227; Rata-rata{{ $unitText }}</td></tr>
                        </table>
                    </td>
                    <td>
                        <table class="lines">
                            <tr><td class="center">&nbsp;</td></tr>
                            <tr><td class="center">{{ $fmt($values->max()) }}</td></tr>
                            <tr><td class="center">{{ $fmt($values->min()) }}</td></tr>
                            <tr><td class="center"><b>{{ is_numeric(str_replace(',', '.', (string) $row['figure'])) ? $fmt(str_replace(',', '.', (string) $row['figure'])) : $row['figure'] }}</b></td></tr>
                        </table>
                    </td>
                @else
                    <td>{{ $row['sequence'] }}.&nbsp; {{ $row['item'] }}</td>
                    <td class="center"><b>{{ $row['figure'] }}</b>{{ $row['unit'] && $row['figure'] !== '-' ? ' ' . $row['unit'] : '' }}</td>
                @endif
                <td>{!! $syarat !== '' ? nl2br(e($syarat)) : '&nbsp;' !!}</td>
                <td class="center">{{ $row['evaluation'] === 'GOOD' ? 'Memenuhi' : ($row['evaluation'] ? 'Tidak Memenuhi' : '-') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ===== IV. KESIMPULAN ===== --}}
<div class="section-title">IV.&nbsp;&nbsp;Kesimpulan</div>
<p style="margin:4px 0 0 14px;">
    Berdasarkan hasil pengujian tersebut di atas, maka contoh {{ $sampleName !== '-' ? strtolower(substr($sampleName, 0, 1)) . substr($sampleName, 1) : 'yang diterima' }}
    yang diuji <b>{{ $allGood ? 'memenuhi' : 'tidak memenuhi' }} syarat mutu</b>.
</p>
@if ($header->comment)
    <p style="margin:4px 0 0 14px;">{{ $header->comment }}</p>
@endif

{{-- ===== TANDA TANGAN ===== --}}
<table class="sig">
    <tr>
        @foreach ($signers as $signer)
            <td class="sig-title">{{ $signer['title'] }}</td>
        @endforeach
    </tr>
    <tr>
        @foreach ($signers as $signer)
            <td style="height:68px;">
                @if ($signer['user'] && $signer['user']->signature)
                    <img src="{{ $signer['user']->signature }}" style="width:85px; height:55px;">
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach ($signers as $signer)
            <td class="sig-name"><b>{{ optional($signer['user'])->name ?: '.........................' }}</b></td>
        @endforeach
    </tr>
</table>
