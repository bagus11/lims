<!DOCTYPE html>
<html>
<head>
    <style>
        @media print {
            body { margin: 20px; }
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #333;
            padding: 6px 4px;
            vertical-align: top;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .numeric {
            text-align: right;
            font-family: monospace;
            min-width: 60px;
        }
        .no-border { border: none; }
        .title {
            font-size: 16px;
            font-weight: bold;
        }
        .header-info { font-size: 10px; line-height: 1.3; }
        .summary-bold { font-weight: bold; background: #f5f5f5; }
        .small { font-size: 9px; }
        img { max-width: 100%; height: auto; }
        .header-info td {
            padding: 2px 4px !important;
            line-height: 1.2;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center">INSPECTION REPORT</h2>
<table>
    {{-- HEADER --}}

   <tr>
        <td class="header-info" style="vertical-align:top;">
            <table style="border:none;">
                <tr>
                    <td style="border:none; width:90px;">Inspection Code</td>
                    <td style="border:none; width:10px;">:</td>
                    <td style="border:none;">{{ $detail->inspection_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border:none; width:90px;">Tanggal</td>
                    <td style="border:none; width:10px;">:</td>
                    <td style="border:none;">
                        {{ $detail->created_at ? \Carbon\Carbon::parse($detail->created_at)->format('d M Y') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td style="border:none;">Produk</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->materialRelation->name }}</td>
                </tr>
                <tr>
                    <td style="border:none;">Supplier</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->supplierRelation->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border:none;">Merk</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->merk ?? 'N/A' }}</td>
                </tr>
            </table>
        </td>

        <td class="header-info" style="vertical-align:top;">
            <table style="border:none;">
                <tr>
                    <td style="border:none; width:100px;">Jumlah</td>
                    <td style="border:none; width:10px;">:</td>
                    <td style="border:none;">{{ $header->amount ?? '' }} Kg</td>
                </tr>
                <tr>
                    <td style="border:none;">Sertifikat</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->certificate ?? '' }}</td>
                </tr>
                <tr>
                    <td style="border:none;">Standard</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->standard ?? '' }}</td>
                </tr>
                <tr>
                    <td style="border:none;">No Lot</td>
                    <td style="border:none;">:</td>
                    <td style="border:none;">{{ $detail->no_lot ?? '' }}</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- BODY --}}
    <tr width="100%">

        {{-- GRAFIK --}}
        <td width="60%">

            <b>GRAFIK</b>
            <br><br>
            @if($chartUrl)
             <img src="{{ $chartUrl }}" style="width:70%; max-width:70%; height:auto;">
            @else
                <div style="padding:20px; text-align:center; color:#999; font-style:italic;">Grafik tidak tersedia</div>
            @endif

        </td>


        {{-- SAMPLE --}}
        <td width="40%" class="header-info">
               <table style="border:none;" border="0">
            <tr class="summary-bold">
                <td style="border:none;"><b>Spec</b></td>
                <td class="numeric" style="border:none;">{{$detail->materialRelation->spec}} gr/cc</td>
            </tr>

            </table>
            <br>
            <table>
                @php
                    $count = count($data);

                @endphp
                <tr class="summary-bold" >
                    <td style="text-align: center"><b>Sample</b></td>
                    <td style="text-align: center">value</td>
                </tr>
                @for($i = 0; $i < $count; $i++)
                <tr>
                    <td>Sample {{ $i+1 }}</td>
                    <td class="numeric">
                        @php
                            $val = $data[$i]->value ?? null;
                            echo $val < 1 ? number_format((float)$val, 4) : $val;

                        @endphp
                    </td>
                </tr>
                @endfor

                <tr class="summary-bold">
                    <td><b>MAX</b></td>
                    <td class="numeric">
                        <b>
                            {{ $max->value  < 1 ? number_format((float) $max->value, 4) : $max->value }}
                        </b>
                    </td>
                </tr>
                <tr class="summary-bold">
                    <td><b>MIN</b></td>
                    <td class="numeric">
                        <b>
                            {{ $min->value  < 1 ? number_format((float) $min->value, 4) : $min->value}}
                        </b>
                    </td>
                </tr>
                <tr class="summary-bold">
                    <td><b>RATA-RATA</b></td>
                    <td class="numeric">
                        <b>
                           {{ $avg  < 1 ? number_format((float) $avg, 4) : $avg}}
                        </b>
                    </td>
                </tr>

            </table>
            <br>
          <table style="border:none;" border="0">
            <tr class="summary-bold">
                <td style="border:none;"><b>SIFAT TAMPAK</b></td>
                <td class="numeric" style="border:none;"></td>
            </tr>
            <tr>
                <td style="border:none;">KEMASAN</td>
                <td class="numeric" style="border:none;">{{ $header->packaging ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">WARNA</td>
                <td class="numeric" style="border:none;">{{ $header->color ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">PARTIKEL SIZE</td>
                <td class="numeric" style="border:none;">{{ $header->particle->size ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">VOLATILE MATTER</td>
                <td class="numeric" style="border:none;">{{ $header->volatile_matter ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">JUMLAH SAK</td>
                <td class="numeric" style="border:none;">{{ $header->amount ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">BERAT / SAK</td>
                <td class="numeric" style="border:none;">{{ $header->weight ?? 'N/A' }}</td>
            </tr>
            </table>

        </td>

    </tr>
    <tr>
      <td colspan="2" class="header-info" style="vertical-align:top;">

            <table style="border:none;">
                <tr>
                    <td style="border:none; width:90px;">No POF</td>
                    <td style="border:none; width:10px;">:</td>
                    <td style="border:none;">{{ $detail->no_pof ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border:none; width:90px;">Keterangan</td>
                    <td style="border:none; width:10px;">:</td>
                    <td style="border:none;">{{ $detail->remark ?? 'N/A' }}</td>
                </tr>

            </table>
        </td>
    </tr>

    {{-- KETERANGAN --}}


    {{-- TANDA TANGAN --}}
     <tr>
        <td colspan="2" style="padding:0; border:none;">

           <table class="table" id="approval_remark" style="table-layout: fixed">
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
                                            $title = "Approver " . ($i + 1); // Fallback title for additional columns if needed
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
                                $signature = isset($approval[$i]) && $approval[$i]->status == 1 && isset($approval[$i]->userRelation->signature)
                                    ? $approval[$i]->userRelation->signature
                                    : '';
                            @endphp
                            <td style="text-align: center">
                                @if ($signature)
                                    <img style='display:block; width:70px; height:50px;' id='base64image' src='{{$signature}}' />
                                @endif
                                <p>
                                    {{$approval[$i]->userRelation->name ?? ''}}
                                </p>
                            </td>
                        @endfor
                        <td style="text-align: center">
                            @if ($detail->inspectorRelation)
                            <img style='display:block; width:70px; height:50px;' id='base64image' src='{{$detail->inspectorRelation->signature}}' />
                            <p>
                                {{$detail->inspectorRelation->name}}
                            </p>
                            @else
                            <img style='display:block; width:70px; height:50px;' id='base64image' src='' />
                            @endif
                        </td>
                        </tr>
                    </tbody>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
