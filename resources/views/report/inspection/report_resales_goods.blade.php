<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    @php
        $locationName = $header->locationRelation ? ($header->locationRelation->name ?? ($header->locationRelation->nama_kantor ?? $header->locationRelation->initial ?? '-')) : '-';
        $pr = $header->productRelation;
        $productName = '-';
        $productSize = '';
        $productUom = '';
        if ($pr) {
            $productName = $pr->name ?? '-';
            $productSize = $pr->size ?? '';
            $productUom = $pr->uom ?? '';
        }
        $supplierName = $header->supplierRelation ? ($header->supplierRelation->name ?? '-') : '-';
        $produsenName = $header->produsenRelation ? ($header->produsenRelation->name ?? '-') : '-';
        $inspectorName = $header->inspectorRelation ? ($header->inspectorRelation->name ?? '-') : '-';
        $createdByName = $header->createdByRelation ? ($header->createdByRelation->name ?? '-') : '-';

        // Build spec symbol list (ALL unique symbol from master spec + all detail specs)
        $masterSpecs = $pr && $pr->spesification ? $pr->spesification : [];
        $symbolsAll = [];       // ordered unique
        $symbolStandard = [];   // symbol => master standard value

        foreach($masterSpecs as $ms) {
            if (is_array($ms)) {
                $sym = trim($ms['symbol'] ?? '');
                $val = trim($ms['value'] ?? '');
            } else {
                $sym = trim($ms);
                $val = '';
            }
            if ($sym === '') continue;
            if (!in_array($sym, $symbolsAll, true)) $symbolsAll[] = $sym;
            $symbolStandard[$sym] = $val;
        }
        // Add custom symbols dari detail specs (yang ga ada di master)
        foreach($details as $d) {
            $sp = $d->spesification ?? [];
            foreach($sp as $s) {
                if (!is_array($s)) continue;
                $sym = trim($s['symbol'] ?? '');
                if ($sym === '') continue;
                if (!in_array($sym, $symbolsAll, true)) $symbolsAll[] = $sym;
            }
        }

        $nSamples = count($details);
        $symbolCount = max(1, count($symbolsAll));
    @endphp

    <h4 style="text-align: center; margin-top: 0;"><u>RESALES GOODS INSPECTION REPORT</u></h4>
    <table class="table" style="margin-top: 5px !important">
        <tr>
            <th>
                <p>Transaction Code : <b>{{ $header->transaction_code }}</b></p>
            </th>
            <th>
                <p>Location : <b>{{ $locationName }}</b></p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Date / Shift : <b>{{ \Carbon\Carbon::parse($header->created_at)->format('d F Y') }}</b></p>
            </th>
            <th>
                <p>Quantity : <b>{{ number_format($header->quantity, 0, ',', '.') }}</b>{!! $productUom ? ' <span style="font-size:9px; color:#555;">('.$productUom.')</span>' : '' !!}</p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Product : <b>{{ $productName }}</b>{!! $productSize ? ' <i style="color:#333;">(Size: '.$productSize.')</i>' : '' !!}</p>
            </th>
            <th>
                <p>Supplier : <b>{{ $supplierName }}</b></p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Produsen : <b>{{ $produsenName }}</b></p>
            </th>
            <th>
                <p>Inspector : <b>{{ $inspectorName }}</b></p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Standard : <b>{{ $header->standard ? $header->standard : '-' }}</b></p>
            </th>
            <th>
                <p>Certificate : <b>{{ $header->certificate ? $header->certificate : '-' }}</b></p>
            </th>
        </tr>
        <tr>
            <th>
                <p>Brand / Merek : <b>{{ $header->brand ? $header->brand : '-' }}</b></p>
            </th>
            <th>
                <p>Packaging / Kemasan : <b>{{ $header->packaging ? $header->packaging : '-' }}</b></p>
            </th>
        </tr>
        @if ($header->type)
        <tr>
            <th>
                <p>Type : <b>{{ $header->type }}</b></p>
            </th>
            <th>
                <p>Created By : <b>{{ $createdByName }}</b></p>
            </th>
        </tr>
        @endif
    </table>

    <table class="table-stepper" style="margin-top : 5px !important">
        <thead>
            <tr>
                <th rowspan="3" style="width: 20%; background-color: #26577C; color: white;">Inspection Item (Symbol)</th>
                <th colspan="{{ $symbolCount }}" style="background-color: #26577C; color: white;">Sample Measurement</th>
            </tr>
            <tr>
                @foreach($symbolsAll as $sym)
                    <th style="background-color: #f7f7f7; color:#111;">{{ $sym }}</th>
                @endforeach
                @if (count($symbolsAll) === 0)
                    <th style="background-color: #f7f7f7;">-</th>
                @endif
            </tr>
            <tr>
                @foreach($symbolsAll as $sym)
                    <th style="background-color: #ffffff; color:#222; font-weight: normal;">
                        Standard
                        <br>
                        <b>{!! isset($symbolStandard[$sym]) && trim($symbolStandard[$sym]) !== '' ? $symbolStandard[$sym] : '<span style="color:#888;">-</span>' !!}</b>
                    </th>
                @endforeach
                @if (count($symbolsAll) === 0)
                    <th>-</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if ($nSamples === 0)
                <tr>
                    <td style="text-align: left; padding-left: 8px; font-weight: bold;">(No Sample Added)</td>
                    <td colspan="{{ $symbolCount }}" style="color:#888;">Silakan tambahkan detail sample row terlebih dahulu.</td>
                </tr>
            @else
                @foreach($details as $idx => $detail)
                    @php
                        $actualBySym = [];
                        $sp = $detail->spesification ?? [];
                        foreach($sp as $s) {
                            if (!is_array($s)) continue;
                            $ss = trim($s['symbol'] ?? '');
                            $vv = trim($s['value'] ?? '');
                            if ($ss === '') continue;
                            $actualBySym[$ss] = $vv;
                        }
                    @endphp
                    <tr>
                        <td style="text-align: left; padding-left: 8px;">
                            <b>Sample {{ $idx + 1 }}</b> / {{ $nSamples }}
                            <br>
                            <span style="color:#777; font-size:8px;">
                                {{ $detail->created_at ? \Carbon\Carbon::parse($detail->created_at)->format('d M Y H:i') : '' }}
                            </span>
                        </td>
                        @foreach($symbolsAll as $sym)
                            @php
                                $v = $actualBySym[$sym] ?? '';
                            @endphp
                            <td style="text-align: center;">{!! $v !== '' ? '<b>'.$v.'</b>' : '<span style="color:#bbb;">&mdash;</span>' !!}</td>
                        @endforeach
                        @if (count($symbolsAll) === 0)
                            <td>&nbsp;</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <table class="table" style="margin-top: 5px !important;">
        <tr>
            <th style="width: 50%; text-align: left;">
                <p><b>NO POF</b> : {{ $header->no_pf ? $header->no_pf : '-' }}</p>
                <p><b>KETERANGAN</b> : {!! $header->keterangan ? nl2br(e($header->keterangan)) : '<span style="color:#999;">-</span>' !!}</p>
            </th>
            <th style="width: 50%; text-align: right;">
                <p style="font-style: italic; font-size: 9px; color: #555;">
                    Satuan pengukuran dalam (mm)
                </p>
            </th>
        </tr>
    </table>

    <b class="remark" style="margin-top:5px">Remark : </b>
    <ul class="a">
        <li> Status transaksi saat print : <b>
            @php
                switch ((int)$header->status) {
                    case 0: $statusTxt = 'Draft'; break;
                    case 1: $statusTxt = 'In Progress'; break;
                    case 2: $statusTxt = 'Approval Progress'; break;
                    case 3: $statusTxt = 'Locked / Done'; break;
                    default: $statusTxt = 'Unknown'; break;
                }
                echo $statusTxt;
            @endphp
        </b></li>
        @if ($header->certificate)
        <li> Nomor Sertifikat : <b>{{ $header->certificate }}</b></li>
        @endif
        @if ($header->standard)
        <li> Standart Acuan : <b>{{ $header->standard }}</b></li>
        @endif
    </ul>

    <table class="table" id="approval_remark">
        <thead>
            <tr>
                @for ($i = 3 - 1; $i >= 0; $i--)
                    @php
                        $title = '';
                        switch ($i) {
                            case 0:
                                $title = "Karu Inspector";
                                break;
                            case 1:
                                $title = "Kabag QC";
                                break;
                            case 2:
                                $title = "Factory Manager";
                                break;
                            default:
                                $title = "Approver " . ($i + 1);
                                break;
                        }
                    @endphp
                    <th>{{ $title }}</th>
                @endfor
                <th>Inspector</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @for ($i = 3 - 1; $i >= 0; $i--)
                    @php
                        $signature = isset($set_approval[$i]) && is_object($set_approval[$i]) && $set_approval[$i]->status == 1 && isset($approval[$i]) && is_object($approval[$i]) && isset($approval[$i]->userRelation->signature)
                            ? $approval[$i]->userRelation->signature
                            : '';
                        $nameText = isset($approval[$i]) && is_object($approval[$i]) && isset($approval[$i]->userRelation->name)
                            ? $approval[$i]->userRelation->name
                            : '';
                    @endphp
                    <td style="text-align: center">
                        @if ($signature)
                            <img style='display:block; margin: 0 auto; width:70px; height:50px;' id='base64image' src='{{$signature}}' />
                        @endif
                        <p>
                            {{$nameText}}
                        </p>
                    </td>
                @endfor
                <td style="text-align: center">
                    @if (!empty($header->inspectorRelation) && !empty($header->inspectorRelation->signature))
                        <img style='display:block; margin: 0 auto; width:70px; height:50px;' id='base64image' src='{{$header->inspectorRelation->signature}}' />
                    @endif
                    <p>
                        {{$inspectorName}}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
<style>
    .remark{
        font-size: 10px !important;
    }
    ul.a {
        list-style-type: circle;
        font-size: 9px !important;
    }
    ul.a li{
        margin-bottom: 5px !important;
    }

    .table-stepper{
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      border-spacing: 0;
      font-size: 9px;
      width: 100% !important;
      border: 1px solid #ddd;

}
  .table-stepper tr:nth-child(even){background-color: #f2f2f2;}

  .table-stepper tr:hover {background-color: #ddd;}

  .table-stepper th {
      border: 1px solid #ddd;
      padding-top: 5px;
      padding-bottom: 5px;
      font-size: 9px;
      text-align: center;
      background-color: #26577C;
      color: white;
      overflow-x:auto !important;
  }
  .table-stepper td, .datatable-stepper th {
      border: 1px solid #ddd;
      padding: 4px;
  }
  table tr:last-child {
      font-weight: bold;
  }
  p{
    font-size: 9px !important;
  }

.table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100% !important;
    font-size: 12px;
}

.table th, .table td {
    border: none;
    padding: 4px;
}

.table th {
    padding-top: 4px;
    padding-bottom: 4px;
    font-size: 9px !important;
    text-align: left;
    color: black;
}
#approver-remark-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding-top: 5px;
        }
        #approval_remark th {
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 9px !important;
            text-align: center;
            color: black;
        }
</style>
