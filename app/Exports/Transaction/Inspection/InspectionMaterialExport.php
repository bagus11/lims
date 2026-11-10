<?php

namespace App\Exports\Transaction\Inspection;

use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Material\Pellet;
use App\Models\Transaction\Inspection\Material\ResinDetail;
use App\Models\Transaction\Inspection\Material\ResinModel;
use App\Models\Transaction\Material\InspectionMaterial;
use Carbon\Carbon;

/**
 * Report Excel satu transaksi Inspection Material (resin type=1, pellet
 * type=2) — isi mengikuti report PDF report-resin_material /
 * report-pellet_material.
 */
class InspectionMaterialExport extends NativeReportExport
{
    protected $inspectionCode;

    public function __construct($inspectionCode)
    {
        $this->inspectionCode = $inspectionCode;
    }

    public function title(): string
    {
        return 'Inspection Material Report';
    }

    protected function build(): void
    {
        $detail = InspectionMaterial::with([
            'locationRelation', 'materialRelation', 'inspectorRelation', 'supplierRelation',
        ])->where('inspection_code', $this->inspectionCode)->firstOrFail();

        $approval = ApprovalHistory::with('userRelation')
            ->where('transaction_code', $detail->inspection_code)->get();

        $r = $this->row(['INSPECTION REPORT']);
        $this->merge("A{$r}:F{$r}");
        $this->bold("A{$r}");
        $this->center("A{$r}");
        $this->fontSize("A{$r}", 14);
        $this->row([]);

        $kvPairs = [
            ['Inspection Code', $detail->inspection_code, 'Tanggal', $detail->created_at ? Carbon::parse($detail->created_at)->format('d M Y') : null],
            ['Produk', optional($detail->materialRelation)->name, 'Supplier', optional($detail->supplierRelation)->name],
            ['Merk', $detail->merk, 'Sertifikat', $detail->certificate],
            ['Standard', $detail->standard, 'No Lot', $detail->no_lot],
        ];

        if ((int) $detail->type === 2) {
            $header = Pellet::with('produsenRelation')->where('inspection_code', $this->inspectionCode)->first();
            $kvPairs[] = ['Produsen', optional(optional($header)->produsenRelation)->name, 'Jumlah', $detail->amount !== null ? $detail->amount . ' Kg' : null];
            $kvPairs[] = ['Sifat Tampak', optional($header)->visible_properties, 'Kemasan', optional($header)->packaging];
            $kvPairs[] = ['Jumlah Sak', optional($header)->amount_per_sak !== null ? optional($header)->amount_per_sak . ' Sak' : null, 'Berat / Sak', optional($header)->weight_per_sak !== null ? optional($header)->weight_per_sak . ' kg' : null];
            $kvPairs[] = ['MFR', optional($header)->mfr !== null ? optional($header)->mfr . ' G/10 mm' : null, null, null];
            $kvPairs[] = ['No POF', $detail->no_pof, 'Keterangan', $detail->remark];

            foreach ($kvPairs as $pair) {
                $this->writeKv($pair);
            }
        } else {
            $header = ResinModel::where('inspection_code', $this->inspectionCode)->first();
            $samples = ResinDetail::where('inspection_code', $this->inspectionCode)->orderBy('step')->get();

            $kvPairs[] = ['Jumlah', optional($header)->amount !== null ? optional($header)->amount . ' Kg' : null, 'Spec', optional($detail->materialRelation)->spec !== null ? optional($detail->materialRelation)->spec . ' gr/cc' : null];
            $kvPairs[] = ['No POF', $detail->no_pof, 'Keterangan', $detail->remark];
            foreach ($kvPairs as $pair) {
                $this->writeKv($pair);
            }
            $this->row([]);

            // tabel sample + MAX/MIN/RATA-RATA (data grafik di PDF)
            $head = $this->row(['Sample', 'Value']);
            $this->bold("A{$head}:B{$head}");
            $this->fill("A{$head}:B{$head}");
            $fmt = function ($v) {
                return $v !== null && (float) $v < 1 ? number_format((float) $v, 4) : $v;
            };
            foreach ($samples as $i => $s) {
                $this->row(['Sample ' . ($i + 1), $fmt($s->value)]);
            }
            if ($samples->isNotEmpty()) {
                $values = $samples->pluck('value')->map(fn ($v) => (float) $v);
                $maxR = $this->row(['MAX', $fmt($values->max())]);
                $this->row(['MIN', $fmt($values->min())]);
                $avgR = $this->row(['RATA-RATA', $fmt($values->avg())]);
                $this->bold("A{$maxR}:B{$avgR}");
            }
            $tableEnd = $this->currentRow();
            $this->border("A{$head}:B{$tableEnd}");
            $this->center("B{$head}:B{$tableEnd}");
            $this->row([]);

            $sifat = $this->row(['SIFAT TAMPAK']);
            $this->bold("A{$sifat}");
            $this->writeKv(['Kemasan', optional($header)->packaging, 'Warna', optional($header)->color]);
            $this->writeKv(['Partikel Size', optional($header)->particle_size, 'Volatile Matter', optional($header)->volatile_matter]);
            $this->writeKv(['Jumlah Sak', optional($header)->amount, 'Berat / Sak', optional($header)->weight]);
        }
        $this->row([]);

        // tanda tangan: urutan sama dengan PDF (step tertinggi dulu, inspector terakhir)
        $labels = ['Factory Manager', 'Kabag QC', 'Karu Inspector', 'Inspector'];
        $names = [
            optional(optional($approval->get(2))->userRelation)->name ?: '',
            optional(optional($approval->get(1))->userRelation)->name ?: '',
            optional(optional($approval->get(0))->userRelation)->name ?: '',
            optional($detail->inspectorRelation)->name ?: '',
        ];
        $sigLabel = $this->row($labels);
        $sigName = $this->row($names);
        $this->bold("A{$sigLabel}:D{$sigLabel}");
        $this->center("A{$sigLabel}:D{$sigName}");
        $this->fill("A{$sigLabel}:D{$sigLabel}");
        $this->border("A{$sigLabel}:D{$sigName}");
        $this->rowHeight($sigName, 46);

        // C cukup lebar karena blok tanda tangan memakai kolom A-D
        foreach (['A' => 24, 'B' => 28, 'C' => 18, 'D' => 24, 'E' => 28, 'F' => 14] as $col => $w) {
            $this->colWidth($col, $w);
        }
    }

    protected function writeKv(array $pair): void
    {
        $r = $this->row([
            $pair[0],
            ': ' . ($pair[1] !== null && $pair[1] !== '' ? $pair[1] : '-'),
            null,
            $pair[2],
            $pair[2] !== null ? ': ' . ($pair[3] !== null && $pair[3] !== '' ? $pair[3] : '-') : null,
        ]);
        $this->bold("A{$r}:A{$r}");
        $this->bold("D{$r}:D{$r}");
    }
}
