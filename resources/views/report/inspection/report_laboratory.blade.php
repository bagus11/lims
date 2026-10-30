@php
    /**
     * Record of Laboratory Testing -- form 03/QC/12-1999 Rev-00.
     * Rendered by mpdf for the PDF and by Maatwebsite (FromView) for the
     * spreadsheet; $excel drops the bits PhpSpreadsheet's HTML reader cannot
     * digest (nested tables, images).
     */
    $excel = $excel ?? false;

    $months = [1 => 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
    $testDate = $header->test_date ? $header->test_date->format('j') . ' ' . $months[(int) $header->test_date->format('n')] . ' ' . $header->test_date->format('Y') : '';

    $romanShift = [1 => 'I', 2 => 'II', 3 => 'III'];
    $shiftTempHumd = array_filter([
        $header->shift ? ($romanShift[(int) $header->shift] ?? $header->shift) : null,
        $header->temperature,
        $header->humidity,
    ]);

    $product = $header->productRelation;
    $specification = $product ? trim($product->seri . ' ' . $product->ukuran . ' ' . $product->marking) : '-';

    $methodCode = optional(optional($header->productMethodRelation)->methodRelation)->code ?: data_get($header->method_snapshot, 'code');
    $methodApplied = strtoupper((string) $methodCode);

    $productTypes = ['Pralon', 'PE Pipe', 'MS K 6741', 'Telkom', 'Fitting', 'Electric'];

    // the four signature slots are fixed by the form; names come from whoever
    // actually approved, in step order, and stay blank when nobody has yet
    $approvers = $history->where('status', 1)->pluck('userRelation.name')->filter()->values();
    $signatures = [
        'V. FACTORY MGR.' => $approvers->get(0),
        'VI. QC. Chift' => $approvers->get(1),
        'VII. Reporter' => optional($header->userRelation)->name,
        'VIII. Kana' => $approvers->get(2),
    ];

    $tick = function ($checked) {
        return '<span style="border:1px solid #000; padding:0 3px; font-size:7pt;">' . ($checked ? 'X' : '&nbsp;&nbsp;') . '</span>';
    };
@endphp

<style>
    body, table { font-family: sans-serif; font-size: 8pt; color: #000; }
    table { border-collapse: collapse; width: 100%; }
    .frame td, .frame th { border: 1px solid #000; padding: 2px 4px; vertical-align: top; }
    .no-border td { border: 0; padding: 1px 2px; }
    .title { font-size: 11pt; font-weight: bold; text-align: center; }
    .section { font-weight: bold; }
    .dotted { border-bottom: 1px solid #000; }
    .center { text-align: center; }
    .right { text-align: right; }
    th { background: #eee; font-weight: bold; text-align: center; }
</style>

<table class="frame">
    <tr>
        <td style="width:20%" class="center">
            @if (!$excel && $logo)
                <img src="{{ $logo }}" style="width:90px">
            @else
                <b style="font-size:12pt">PRALON</b>
            @endif
        </td>
        <td style="width:30%">REPORT NO : <b>{{ $header->report_no ?: '-' }}</b></td>
        <td style="width:35%" class="title">
            RECORD OF LABORATORY TESTING<br>
            <span style="font-size:7pt">{{ $header->laboratory_code }}</span>
        </td>
        <td style="width:15%" class="center">ISO 9001<br>KAN</td>
    </tr>
</table>

<table class="frame">
    <tr>
        <td colspan="4" class="section">I&nbsp;&nbsp; TESTING CONDITION</td>
    </tr>
    <tr>
        <td style="width:25%">Date OF Tested</td>
        <td style="width:30%">: <b>{{ $testDate ?: '-' }}</b></td>
        <td style="width:22%">Location</td>
        <td style="width:23%">: {{ optional($header->locationRelation)->name ?: '-' }}</td>
    </tr>
    <tr>
        <td colspan="4" class="section">II&nbsp;&nbsp; SAMPLE TESTED</td>
    </tr>
    <tr>
        <td>Production code Number</td>
        <td>: {{ $header->production_code_number ?: '-' }}</td>
        <td>Shift / Temp. / Humd.</td>
        <td>: {{ $shiftTempHumd ? implode(' / ', $shiftTempHumd) : '-' }}</td>
    </tr>
    <tr>
        <td>Material Lot Number/Formula</td>
        <td>: {{ $header->material_lot_number ?: '-' }}</td>
        <td>Machine Number</td>
        <td>: {{ optional($header->machineRelation)->no_machine ?: '-' }}</td>
    </tr>
    <tr>
        <td>Specification Of Pipe Sample</td>
        <td colspan="3">: {{ $specification }}</td>
    </tr>
    <tr>
        <td colspan="4" class="section">III&nbsp;&nbsp; TEST RESULT :</td>
    </tr>
    <tr>
        <td>Test Method Aplied</td>
        <td>
            {!! $tick(str_contains($methodApplied, 'SNI')) !!} SNI.06-0084-2002 &nbsp;
            {!! $tick(str_contains($methodApplied, 'ISO')) !!} ISO
        </td>
        <td colspan="2">
            @foreach ($productTypes as $type)
                {!! $tick($header->product_type === $type) !!} {{ $type }}@if (!$loop->last) &nbsp;@endif
                @if ($loop->iteration % 3 === 0)<br>@endif
            @endforeach
        </td>
    </tr>
</table>

<table class="frame">
    <thead>
        <tr>
            <th style="width:4%">NO</th>
            <th style="width:15%">ITEM</th>
            <th style="width:25%">SPECIES</th>
            <th style="width:13%">SPECIFICATION</th>
            <th style="width:22%">FIGURE MEASURED</th>
            <th style="width:11%">INCHARGE</th>
            <th style="width:10%">EVALUATION</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td class="center">{{ $row['sequence'] }}</td>
                <td>{{ $row['item'] }}</td>
                <td>{!! $row['species'] ? nl2br(e($row['species'])) : '&nbsp;' !!}</td>
                <td class="center">{{ $row['specification'] }}</td>
                <td>
                    @if (count($row['measurements']) > 1)
                        @if ($excel)
                            {{ implode(' | ', array_map(fn ($value) => rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.'), $row['measurements'])) }}<br>
                        @else
                            <table class="no-border" style="width:100%">
                                @foreach (array_chunk($row['measurements'], 2) as $pair)
                                    <tr>
                                        @foreach ($pair as $value)
                                            <td style="width:50%" class="center">{{ rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.') }}</td>
                                        @endforeach
                                        @if (count($pair) === 1)<td style="width:50%"></td>@endif
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <div class="center"><b>Average : {{ $row['figure'] }}{{ $row['unit'] ? ' ' . $row['unit'] : '' }}</b></div>
                    @else
                        <div class="center"><b>{{ $row['figure'] }}{{ $row['unit'] && $row['figure'] !== '-' ? ' ' . $row['unit'] : '' }}</b></div>
                    @endif
                </td>
                <td class="center">{{ $row['in_charge'] ?: '-' }}</td>
                <td class="center">{{ $row['evaluation'] === 'GOOD' ? 'Good' : ($row['evaluation'] ? 'No Good' : '-') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="frame">
    <tr>
        <td style="width:15%" class="section">IV&nbsp;&nbsp; COMMENT :</td>
        <td style="width:85%">{!! $header->comment ? nl2br(e($header->comment)) : '&nbsp;' !!}</td>
    </tr>
</table>

<table class="frame">
    <tr>
        @foreach ($signatures as $label => $name)
            <th style="width:25%">{{ $label }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach ($signatures as $label => $name)
            <td class="center" style="height:55px; vertical-align:bottom;">
                {{ $name ?: '' }}
                <div style="border-top:1px solid #000; margin-top:2px;">&nbsp;</div>
            </td>
        @endforeach
    </tr>
</table>

<table class="no-border">
    <tr>
        <td style="font-size:7pt">03/QC/12-1999 Rev-00</td>
        <td style="font-size:7pt" class="right">Dicetak otomatis oleh LIMS PT Pralon &middot; {{ date('d/m/Y H:i') }}</td>
    </tr>
</table>
