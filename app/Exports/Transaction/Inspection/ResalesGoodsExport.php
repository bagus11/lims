<?php

namespace App\Exports\Transaction\Inspection;

use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\ResalesGood;
use App\Models\Transaction\Inspection\ResalesGoodDetail;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Report Excel satu transaksi Resales Goods — data sama dengan PDF
 * (report_resales_goods.blade.php), dibangun native.
 */
class ResalesGoodsExport extends NativeReportExport
{
    protected $transaction_code;

    public function __construct($transaction_code)
    {
        $this->transaction_code = $transaction_code;
    }

    public function title(): string
    {
        return 'Resales Goods Report';
    }

    protected function build(): void
    {
        $header = ResalesGood::with([
            'locationRelation', 'productRelation', 'supplierRelation',
            'produsenRelation', 'inspectorRelation', 'createdByRelation',
        ])->where('transaction_code', $this->transaction_code)->firstOrFail();

        $details = ResalesGoodDetail::where('transaction_code', $this->transaction_code)
            ->orderBy('id')->get();

        $approval = collect();
        if (ApprovalHistory::where('transaction_code', $this->transaction_code)->exists()) {
            $approvalHeader = ApprovalHeader::where([
                'inspection_type' => 4,
                'type' => 1,
                'location' => $header->location_id,
            ])->first();
            if ($approvalHeader) {
                $approval = ApprovalDetail::with('userRelation')
                    ->where('approval_code', $approvalHeader->approval_code)
                    ->orderBy('step')->get();
            }
        }

        $location = $header->locationRelation;
        $locationName = $location ? ($location->name ?? $location->nama_kantor ?? $location->initial ?? '-') : '-';
        $pr = $header->productRelation;

        // daftar simbol: master spec + simbol custom dari detail (logika sama dengan blade)
        $symbols = [];
        $symbolStandard = [];
        foreach (($pr && $pr->spesification ? $pr->spesification : []) as $ms) {
            $sym = is_array($ms) ? trim($ms['symbol'] ?? '') : trim($ms);
            if ($sym === '') continue;
            if (!in_array($sym, $symbols, true)) $symbols[] = $sym;
            $symbolStandard[$sym] = is_array($ms) ? trim($ms['value'] ?? '') : '';
        }
        foreach ($details as $d) {
            foreach (($d->spesification ?? []) as $s) {
                if (!is_array($s)) continue;
                $sym = trim($s['symbol'] ?? '');
                if ($sym !== '' && !in_array($sym, $symbols, true)) $symbols[] = $sym;
            }
        }

        $lastCol = Coordinate::stringFromColumnIndex(max(2, count($symbols) + 1));

        $r = $this->row(['RESALES GOODS INSPECTION REPORT']);
        $this->merge("A{$r}:{$lastCol}{$r}");
        $this->bold("A{$r}:A{$r}");
        $this->center("A{$r}:A{$r}");
        $this->fontSize("A{$r}", 14);
        $this->row([]);

        $statusMap = [0 => 'Draft', 1 => 'In Progress', 2 => 'Approval Progress', 3 => 'Locked / Done'];
        $kv = [
            ['Transaction Code', $header->transaction_code, 'Location', $locationName],
            ['Date', Carbon::parse($header->created_at)->format('d F Y'), 'Quantity', number_format($header->quantity, 0, ',', '.') . ($pr && $pr->uom ? ' ' . $pr->uom : '')],
            ['Product', ($pr->name ?? '-') . ($pr && $pr->size ? ' (Size: ' . $pr->size . ')' : ''), 'Supplier', optional($header->supplierRelation)->name],
            ['Produsen', optional($header->produsenRelation)->name, 'Inspector', optional($header->inspectorRelation)->name],
            ['Standard', $header->standard, 'Certificate', $header->certificate],
            ['Brand / Merek', $header->brand, 'Packaging / Kemasan', $header->packaging],
            ['Type', $header->type, 'Created By', optional($header->createdByRelation)->name],
            ['No POF', $header->no_pf, 'Status saat print', $statusMap[(int) $header->status] ?? 'Unknown'],
            ['Keterangan', $header->keterangan, 'Satuan ukur', 'mm'],
        ];
        foreach ($kv as $pair) {
            $r = $this->row([$pair[0], ': ' . ($pair[1] !== null && $pair[1] !== '' ? $pair[1] : '-'), null, $pair[2], ': ' . ($pair[3] !== null && $pair[3] !== '' ? $pair[3] : '-')]);
            $this->bold("A{$r}:A{$r}");
            $this->bold("D{$r}:D{$r}");
        }
        $this->row([]);

        // tabel pengukuran: baris pertama simbol, kedua standard, lalu sample
        $headRow = $this->row(array_merge(['Inspection Item (Symbol)'], $symbols ?: ['-']));
        $stdRow = $this->row(array_merge(['Standard'], array_map(function ($sym) use ($symbolStandard) {
            return ($symbolStandard[$sym] ?? '') !== '' ? $symbolStandard[$sym] : '-';
        }, $symbols ?: ['-'])));
        $this->bold("A{$headRow}:{$lastCol}{$headRow}");
        $this->fill("A{$headRow}:{$lastCol}{$headRow}");
        $this->bold("A{$stdRow}:A{$stdRow}");

        foreach ($details as $idx => $detail) {
            $bySym = [];
            foreach (($detail->spesification ?? []) as $s) {
                if (!is_array($s)) continue;
                $sym = trim($s['symbol'] ?? '');
                if ($sym !== '') $bySym[$sym] = trim($s['value'] ?? '');
            }
            $label = 'Sample ' . ($idx + 1) . ' / ' . count($details)
                . ($detail->created_at ? ' (' . Carbon::parse($detail->created_at)->format('d M Y H:i') . ')' : '');
            $this->row(array_merge([$label], array_map(function ($sym) use ($bySym) {
                return ($bySym[$sym] ?? '') !== '' ? $bySym[$sym] : '—';
            }, $symbols ?: [''])));
        }
        if ($details->isEmpty()) {
            $this->row(['(No Sample Added)']);
        }
        $tableEnd = $this->currentRow();
        $this->border("A{$headRow}:{$lastCol}{$tableEnd}");
        $this->center("B{$headRow}:{$lastCol}{$tableEnd}");
        $this->row([]);

        // tanda tangan: urutan sama dengan PDF (step tertinggi dulu, inspector terakhir)
        $labels = ['Factory Manager', 'Kabag QC', 'Karu Inspector', 'Inspector'];
        $names = [
            optional(optional($approval->get(2))->userRelation)->name ?: '',
            optional(optional($approval->get(1))->userRelation)->name ?: '',
            optional(optional($approval->get(0))->userRelation)->name ?: '',
            optional($header->inspectorRelation)->name ?: '',
        ];
        $sigLabel = $this->row($labels);
        $sigName = $this->row($names);
        $this->bold("A{$sigLabel}:D{$sigLabel}");
        $this->center("A{$sigLabel}:D{$sigName}");
        $this->fill("A{$sigLabel}:D{$sigLabel}");
        $this->border("A{$sigLabel}:D{$sigName}");
        $this->rowHeight($sigName, 46);

        $this->colWidth('A', 34);
        foreach (range(2, count($symbols) + 1) as $i) {
            $this->colWidth(Coordinate::stringFromColumnIndex($i), 16);
        }
        $this->colWidth('D', max(22, 16));
        $this->colWidth('E', 26);
    }
}
