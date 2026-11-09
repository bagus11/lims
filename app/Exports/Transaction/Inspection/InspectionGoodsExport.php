<?php

namespace App\Exports\Transaction\Inspection;

use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\InspectionGoodsSubDetail;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Report Excel satu shift Inspection Goods — matriks item inspeksi x mesin,
 * isi mengikuti report PDF report_inspection_goods.
 */
class InspectionGoodsExport extends NativeReportExport
{
    protected $detailCode;

    public function __construct($detailCode)
    {
        $this->detailCode = $detailCode;
    }

    public function title(): string
    {
        return 'Inspection Goods Report';
    }

    protected function build(): void
    {
        $data = InspectionGoodsSubDetail::with([
            'userRelation', 'machineRelation', 'wallThicknessRelation', 'productRelation',
            'materialRelation', 'headerRelation.locationRelation', 'inspectorRelation', 'detailRelation',
        ])->where('inspection_detail', $this->detailCode)->get();

        if ($data->isEmpty()) {
            $this->row(['Tidak ada data untuk ' . $this->detailCode]);

            return;
        }

        $first = $data->first();
        $lastCol = Coordinate::stringFromColumnIndex($data->count() + 1);

        $r = $this->row(['INSPECTION GOODS REPORT']);
        $this->merge("A{$r}:{$lastCol}{$r}");
        $this->bold("A{$r}");
        $this->center("A{$r}");
        $this->fontSize("A{$r}", 14);
        $this->row([]);

        $kv = [
            ['Inspection Code', $first->inspection_header],
            ['Detail Inspection Code', $first->inspection_detail],
            ['Location', optional(optional($first->headerRelation)->locationRelation)->name],
            ['Shift', optional($first->detailRelation)->shift],
        ];
        foreach ($kv as $pair) {
            $r = $this->row([$pair[0], ': ' . ($pair[1] !== null && $pair[1] !== '' ? $pair[1] : '-')]);
            $this->bold("A{$r}");
        }
        $this->row([]);

        $machineRow = $this->row(array_merge(['Inspection Item'], $data->map(function ($item) {
            return optional($item->machineRelation)->name ?: '-';
        })->all()));
        $this->bold("A{$machineRow}:{$lastCol}{$machineRow}");
        $this->fill("A{$machineRow}:{$lastCol}{$machineRow}");

        $simpleRows = [
            ['Created At', fn ($i) => $i->created_at ? Carbon::parse($i->created_at)->format('d M Y H:i') : '-'],
            ['Specification of pipes', fn ($i) => optional($i->productRelation)->marking],
            ['Length of pipes', fn ($i) => $i->length_of_pipe],
            ['Appearance of pipes', fn ($i) => $i->appearance_of_pipe],
            ['Marking', fn ($i) => $i->marking],
            ['Condition pipe of cut', fn ($i) => $i->condition_pipe_of_cut],
            ['Angle Pipe of Spigot', fn ($i) => $i->angle_pipe_of_spigot],
            ['Color of Pipes', fn ($i) => $i->color_of_pipes],
            ['Pipe Size', fn ($i) => optional($i->productRelation)->marking],
            ['Spec OD (Min - Max)', fn ($i) => optional($i->productRelation)->min_od . ' - ' . optional($i->productRelation)->max_od],
            ['Spec WT (Min - Max)', fn ($i) => optional($i->productRelation)->min_wt . ' - ' . optional($i->productRelation)->max_wt],
            ['Number Of Machine', fn ($i) => optional($i->machineRelation)->no_machine],
            ['Measure used', fn ($i) => optional($i->productRelation)->measure_used],
            ['Tape Diameter', fn ($i) => $i->tape_diameter !== null ? $i->tape_diameter . ' mm' : '-'],
        ];
        foreach ($simpleRows as [$label, $fn]) {
            $this->row(array_merge([$label], $data->map(function ($item) use ($fn) {
                $v = $fn($item);

                return $v !== null && $v !== '' ? $v : '-';
            })->all()));
        }

        // wall thickness: tiap mesin satu sel multiline "step n: 8 titik"
        $wtSteps = function ($item, $reducer) {
            return collect($item->wallThicknessRelation)->map(function ($wt) use ($reducer) {
                $points = [];
                for ($i = 1; $i <= 8; $i++) {
                    $points[] = (float) $wt->{'wt_' . $i};
                }

                return $reducer($points);
            });
        };

        $wtRow = $this->row(array_merge(['Wall Thickness (8 titik / step)'], $data->map(function ($item) {
            return collect($item->wallThicknessRelation)->map(function ($wt, $idx) {
                $points = [];
                for ($i = 1; $i <= 8; $i++) {
                    $points[] = rtrim(rtrim(number_format((float) $wt->{'wt_' . $i}, 3, '.', ''), '0'), '.');
                }

                return 'step ' . ($idx + 1) . ': ' . implode(', ', $points);
            })->implode("\n") ?: '-';
        })->all()));
        $this->wrap("B{$wtRow}:{$lastCol}{$wtRow}");

        $aggRows = [
            ['Max Wall Thickness', fn ($p) => max($p)],
            ['Min Wall Thickness', fn ($p) => min($p)],
            ['Range Wall Thickness', fn ($p) => max($p) - min($p)],
            ['Avg Wall Thickness', fn ($p) => round(array_sum($p) / count($p), 3)],
        ];
        foreach ($aggRows as [$label, $reducer]) {
            $this->row(array_merge([$label], $data->map(function ($item) use ($wtSteps, $reducer) {
                $vals = $wtSteps($item, $reducer);

                return $vals->isEmpty() ? '-' : $vals->map(fn ($v) => rtrim(rtrim(number_format((float) $v, 3, '.', ''), '0'), '.'))->implode(' | ');
            })->all()));
        }

        $this->row(array_merge(['Evaluation Spec'], $data->map(fn ($i) => $i->evaluation_spec ?: '-')->all()));
        $tableEnd = $this->currentRow();
        $this->border("A{$machineRow}:{$lastCol}{$tableEnd}");
        $this->center("B{$machineRow}:{$lastCol}" . ($wtRow - 1));
        $this->center("B" . ($wtRow + 1) . ":{$lastCol}{$tableEnd}");
        $this->row([]);

        $r = $this->row(['Remark :']);
        $this->bold("A{$r}");
        foreach ($data as $item) {
            $this->row([(optional($item->machineRelation)->name ?: '-') . ' : ' . ($item->remark ?: '-')]);
        }
        $this->row([]);

        // tanda tangan: nama approver dari history (urutan step), inspector terakhir
        $approval = ApprovalHistory::with('userRelation')
            ->where('transaction_code', $this->detailCode)->orderBy('step')->get();
        $labels = ['Factory Manager', 'Kabag QC', 'Karu Inspector', 'Inspector'];
        $names = [
            optional(optional($approval->get(2))->userRelation)->name ?: '',
            optional(optional($approval->get(1))->userRelation)->name ?: '',
            optional(optional($approval->get(0))->userRelation)->name ?: '',
            optional($first->inspectorRelation)->name ?: '',
        ];
        $sigLabel = $this->row($labels);
        $sigName = $this->row($names);
        $this->bold("A{$sigLabel}:D{$sigLabel}");
        $this->center("A{$sigLabel}:D{$sigName}");
        $this->fill("A{$sigLabel}:D{$sigLabel}");
        $this->border("A{$sigLabel}:D{$sigName}");
        $this->rowHeight($sigName, 46);

        $this->colWidth('A', 28);
        foreach (range(2, $data->count() + 1) as $i) {
            $this->colWidth(Coordinate::stringFromColumnIndex($i), 34);
        }
    }
}
