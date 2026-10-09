<?php

namespace App\Http\Controllers\Transaction\Inspection;

use App\Exports\Transaction\Inspection\LaboratoryReportExport;
use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Inspection\Laboratory\LockLaboratoryRequest;
use App\Http\Requests\Transaction\Inspection\Laboratory\SaveLaboratoryResultRequest;
use App\Http\Requests\Transaction\Inspection\Laboratory\StoreLaboratoryHeaderRequest;
use App\Models\Master\LaboratoryProductMethod;
use App\Models\Master\Machine;
use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProduct;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Laboratory\LaboratoryDetail;
use App\Models\Transaction\Inspection\Laboratory\LaboratoryHeader;
use App\Models\Transaction\Inspection\Laboratory\LaboratoryMeasurement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use NumConvert;

class LaboratoryController extends Controller
{
    function index()
    {
        return view('transaction.inspection.laboratory.laboratory-index');
    }

    /**
     * Non-GM hanya boleh menyentuh transaksi lokasinya sendiri.
     */
    private function canAccessLocation($locationId): bool
    {
        if (auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            return true;
        }

        return (string) $locationId === (string) auth()->user()->kode_kantor;
    }

    /**
     * Akses baca (detail/print/export): lokasi sendiri, GM, atau approver
     * yang pernah ditugaskan di transaksi ini.
     */
    private function canViewLaboratory(LaboratoryHeader $header): bool
    {
        if ($this->canAccessLocation($header->location_id)) {
            return true;
        }

        return ApprovalHistory::where('transaction_code', $header->laboratory_code)
            ->where('inspection_type', 1)
            ->where('user_id', auth()->user()->id)
            ->exists();
    }

    private function denyAccess(): void
    {
        throw ValidationException::withMessages([
            'laboratory_code' => ['You are not allowed to access this laboratory transaction.'],
        ]);
    }

    function getLaboratoryOptions(Request $request)
    {
        $machineQuery = Machine::where('status', 1);
        if ($request->location_id) {
            $machineQuery->where('location_id', $request->location_id);
        }

        $productIds = LaboratoryProductMethod::where('status', 1)->distinct()->pluck('product_id');

        return response()->json([
            'data' => [
                'products' => MasterProduct::whereIn('id', $productIds)->orderBy('seri')->orderBy('ukuran')->get(),
                'locations' => MasterLocation::all(),
                'machines' => $machineQuery->orderBy('name')->get(),
                'users' => User::select('id', 'name')->orderBy('name')->get(),
            ],
        ]);
    }

    function getLaboratoryProductMethods(Request $request)
    {
        $data = LaboratoryProductMethod::with('methodRelation')
            ->where('product_id', $request->product_id)
            ->where('status', 1)
            ->whereHas('methodRelation', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('parametersRelation', function ($query) {
                $query->where('status', 1);
            })
            ->get();

        return response()->json(['data' => $data]);
    }

    function getProductLaboratory(Request $request)
    {
        $query = LaboratoryHeader::with([
            'productRelation',
            'productMethodRelation.methodRelation',
            'locationRelation',
            'machineRelation',
            'userRelation',
            'approvalUserRelation',
        ]);

        if ($request->from && $request->to) {
            $query->whereBetween('test_date', [$request->from, $request->to]);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // filter location_id dari client hanya dihormati untuk GM; non-GM
        // selalu dikunci ke lokasinya sendiri (tidak bisa dibypass via query)
        if (auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            if ($request->location_id) {
                $query->where('location_id', $request->location_id);
            }
        } else {
            $query->where('location_id', auth()->user()->kode_kantor);
        }

        return response()->json([
            'data' => $query->orderBy('id', 'desc')->get(),
        ]);
    }

    function addProductLaboratory(Request $request, StoreLaboratoryHeaderRequest $storeRequest)
    {
        $storeRequest->validated();

        // Non-GM tidak boleh membuat transaksi atas nama lokasi lain
        $locationId = $request->location_id;
        if (!auth()->user()->hasPermissionSafe('get-only_gm-inspection-goods')) {
            $locationId = auth()->user()->kode_kantor;
        }

        $location = MasterLocation::find($locationId);
        if (!$location) {
            throw ValidationException::withMessages([
                'location_id' => ['Location not found.'],
            ]);
        }

        if ($request->machine_id) {
            $machine = Machine::find($request->machine_id);
            if (!$machine || (int) $machine->location_id !== (int) $locationId) {
                throw ValidationException::withMessages([
                    'machine_id' => ['Machine does not belong to the selected location.'],
                ]);
            }
        }

        $product = MasterProduct::findOrFail($request->product_id);
        $productMethod = LaboratoryProductMethod::with([
            'methodRelation',
            'parametersRelation.parameterRelation',
        ])->where('id', $request->product_method_id)
            ->where('product_id', $request->product_id)
            ->where('status', 1)
            ->first();

        if (!$productMethod || !$productMethod->methodRelation || !$productMethod->methodRelation->status) {
            throw ValidationException::withMessages([
                'product_method_id' => ['The selected laboratory method is not active for this product.'],
            ]);
        }

        $configurations = $productMethod->parametersRelation->where('status', 1)->values();
        if ($configurations->isEmpty()) {
            throw ValidationException::withMessages([
                'product_method_id' => ['The selected laboratory method has no active parameters.'],
            ]);
        }

        $header = DB::transaction(function () use ($request, $product, $productMethod, $configurations, $locationId) {
            $laboratoryCode = $this->generateLaboratoryCode();

            $header = LaboratoryHeader::create([
                'laboratory_code' => $laboratoryCode,
                'report_no' => $request->report_no ? trim($request->report_no) : null,
                'product_id' => $product->id,
                'product_method_id' => $productMethod->id,
                'test_date' => $request->test_date,
                'production_code_number' => $request->production_code_number ? trim($request->production_code_number) : null,
                'material_lot_number' => $request->material_lot_number ? trim($request->material_lot_number) : null,
                'formula_snapshot' => $product->formula,
                'specification_snapshot' => [
                    'product_id' => $product->id,
                    'seri' => $product->seri,
                    'join' => $product->join,
                    'ukuran' => $product->ukuran,
                    'marking' => $product->marking,
                    'formula' => $product->formula,
                ],
                'method_snapshot' => [
                    'id' => $productMethod->methodRelation->id,
                    'code' => $productMethod->methodRelation->code,
                    'name' => $productMethod->methodRelation->name,
                ],
                'location_id' => $locationId,
                'shift' => $request->shift ?: 1,
                'temperature' => $request->temperature ? trim($request->temperature) : null,
                'humidity' => $request->humidity ? trim($request->humidity) : null,
                'product_type' => $request->product_type ?: null,
                'machine_id' => $request->machine_id ?: null,
                'status' => 0,
                'current_approval' => 0,
                'approval_id' => null,
                'lock' => 0,
                'user_id' => auth()->user()->id,
            ]);

            foreach ($configurations as $configuration) {
                $parameter = $configuration->parameterRelation;
                if (!$parameter || !$parameter->status) {
                    continue;
                }

                LaboratoryDetail::create([
                    'laboratory_header_id' => $header->id,
                    'product_method_parameter_id' => $configuration->id,
                    'sequence' => $configuration->sequence,
                    'parameter_name_snapshot' => $parameter->name,
                    'description_snapshot' => $parameter->description,
                    'result_type_snapshot' => $parameter->result_type,
                    'standard_snapshot' => $this->resolveStandard($configuration, $product, $parameter->result_type),
                    'unit_snapshot' => $configuration->unit ?: $parameter->default_unit,
                    'calculation_type_snapshot' => $parameter->result_type === 'numeric' ? $configuration->calculation_type : 'NONE',
                    'measurement_count_snapshot' => $parameter->result_type === 'numeric' ? ($configuration->measurement_count ?: 1) : null,
                    'is_required_snapshot' => $configuration->is_required,
                ]);
            }

            if (!$header->detailsRelation()->exists()) {
                throw ValidationException::withMessages([
                    'product_method_id' => ['No valid active parameter could be generated.'],
                ]);
            }

            return $header;
        });

        return ResponseFormatter::success(
            ['laboratory_code' => $header->laboratory_code],
            'Product laboratory transaction successfully created'
        );
    }

    function getProductLaboratoryDetail(Request $request)
    {
        $header = LaboratoryHeader::with([
            'productRelation',
            'productMethodRelation.methodRelation',
            'locationRelation',
            'machineRelation',
            'userRelation',
            'approvalUserRelation',
            'detailsRelation.measurementsRelation',
            'detailsRelation.inChargeRelation',
            'detailsRelation.checkerRelation',
        ])->where('laboratory_code', $request->laboratory_code)->first();

        if (!$header) {
            return ResponseFormatter::error(null, 'Laboratory transaction not found', 404);
        }

        if (!$this->canViewLaboratory($header)) {
            return ResponseFormatter::error(null, 'You are not allowed to access this laboratory transaction', 403);
        }

        $history = ApprovalHistory::with('userRelation')
            ->where('transaction_code', $header->laboratory_code)
            ->where('inspection_type', 1)
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $header,
            'history' => $history,
        ]);
    }

    function saveLaboratoryMeasurement(Request $request, SaveLaboratoryResultRequest $saveRequest)
    {
        $saveRequest->validated();

        $result = DB::transaction(function () use ($request) {
            $detail = LaboratoryDetail::with('headerRelation')
                ->lockForUpdate()
                ->findOrFail($request->detail_id);
            $header = $detail->headerRelation;

            if (!$this->canAccessLocation($header->location_id)) {
                $this->denyAccess();
            }

            if ($header->lock || !in_array((int) $header->status, [0, 1, 4], true)) {
                throw ValidationException::withMessages([
                    'detail_id' => ['This laboratory transaction is locked and cannot be edited.'],
                ]);
            }

            $post = [
                'in_charge_user_id' => $request->in_charge_user_id ?: null,
                'checker_user_id' => $request->checker_user_id ?: null,
                'remark' => $request->remark ? trim($request->remark) : null,
            ];

            if ($detail->result_type_snapshot === 'numeric') {
                $measurements = array_values(array_filter($request->measurements ?: [], function ($value) {
                    return $value !== null && $value !== '';
                }));
                $expectedCount = $detail->measurement_count_snapshot ?: 1;

                if (count($measurements) !== (int) $expectedCount) {
                    throw ValidationException::withMessages([
                        'measurements' => ['Exactly ' . $expectedCount . ' measurements are required.'],
                    ]);
                }

                LaboratoryMeasurement::where('laboratory_detail_id', $detail->id)->delete();
                foreach ($measurements as $index => $value) {
                    LaboratoryMeasurement::create([
                        'laboratory_detail_id' => $detail->id,
                        'sequence' => $index + 1,
                        'value' => $value,
                    ]);
                }

                $calculated = $this->calculateNumericResult($measurements, $detail->calculation_type_snapshot);
                $post['result_value'] = $calculated;
                $post['text_result'] = null;
                $post['boolean_result'] = null;
                $post['evaluation'] = $this->evaluateNumeric($calculated, $detail->standard_snapshot ?: []);
            } elseif ($detail->result_type_snapshot === 'boolean') {
                if ($detail->is_required_snapshot && $request->boolean_result === null) {
                    throw ValidationException::withMessages([
                        'boolean_result' => ['Boolean result is required.'],
                    ]);
                }

                LaboratoryMeasurement::where('laboratory_detail_id', $detail->id)->delete();
                $post['result_value'] = null;
                $post['text_result'] = null;
                $post['boolean_result'] = $request->boolean_result;
                $post['evaluation'] = $this->evaluateBoolean($request->boolean_result, $detail->standard_snapshot ?: []);
            } else {
                $textResult = $request->text_result !== null ? trim($request->text_result) : null;
                if ($detail->is_required_snapshot && ($textResult === null || $textResult === '')) {
                    throw ValidationException::withMessages([
                        'text_result' => ['Text result is required.'],
                    ]);
                }

                LaboratoryMeasurement::where('laboratory_detail_id', $detail->id)->delete();
                $post['result_value'] = null;
                $post['text_result'] = $textResult;
                $post['boolean_result'] = null;
                $post['evaluation'] = $this->evaluateText($textResult, $detail->standard_snapshot ?: []);
            }

            $detail->update($post);
            if (in_array((int) $header->status, [0, 4], true)) {
                $header->update([
                    'status' => 1,
                    'current_approval' => 0,
                    'approval_id' => null,
                    'completed_at' => null,
                ]);
            }

            return $post;
        });

        return ResponseFormatter::success($result, 'Laboratory result successfully saved');
    }

    function lockProductLaboratory(Request $request, LockLaboratoryRequest $lockRequest)
    {
        $lockRequest->validated();

        $result = DB::transaction(function () use ($request) {
            $header = LaboratoryHeader::with('detailsRelation')
                ->where('laboratory_code', $request->laboratory_code)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$this->canAccessLocation($header->location_id)) {
                $this->denyAccess();
            }

            // status 4 (Revision) boleh langsung submit ulang — perbaikan bisa
            // berupa komentar saja tanpa mengubah hasil ukur
            if ($header->lock || !in_array((int) $header->status, [1, 4], true)) {
                throw ValidationException::withMessages([
                    'laboratory_code' => ['Only an unlocked In Progress or Revision transaction can be submitted.'],
                ]);
            }

            foreach ($header->detailsRelation as $detail) {
                if (!$detail->is_required_snapshot) {
                    continue;
                }

                $missing = $detail->result_type_snapshot === 'numeric'
                    ? $detail->result_value === null
                    : ($detail->result_type_snapshot === 'boolean'
                        ? $detail->boolean_result === null
                        : $detail->text_result === null || trim($detail->text_result) === '');

                if ($missing) {
                    throw ValidationException::withMessages([
                        'laboratory_code' => ['Required result is incomplete: ' . $detail->parameter_name_snapshot . '.'],
                    ]);
                }
            }

            $approval = ApprovalHeader::where([
                'inspection_type' => 1,
                'type' => 1,
                'location' => $header->location_id,
                'shift' => $header->shift ?: 1,
                'status' => 1,
            ])->first();

            if (!$approval) {
                throw ValidationException::withMessages([
                    'laboratory_code' => ['Active Laboratory approval setting was not found for this location and shift.'],
                ]);
            }

            $firstApproval = ApprovalDetail::where('approval_code', $approval->approval_code)
                ->where('step', 1)
                ->first();

            if (!$firstApproval) {
                throw ValidationException::withMessages([
                    'laboratory_code' => ['Laboratory approval step 1 is not configured.'],
                ]);
            }

            $header->update([
                'lock' => 1,
                'status' => 2,
                'current_approval' => 1,
                'approval_id' => $firstApproval->user_id,
                'submitted_at' => now(),
            ]);

            ApprovalHistory::create([
                'transaction_code' => $header->laboratory_code,
                'type' => 1,
                'inspection_type' => 1,
                'step' => 1,
                'user_id' => $firstApproval->user_id,
                'status' => 0,
                'remark' => 'Waiting for Laboratory approval',
            ]);

            return [
                'laboratory_code' => $header->laboratory_code,
                'approval_id' => $firstApproval->user_id,
            ];
        });

        return ResponseFormatter::success($result, 'Laboratory transaction submitted for approval');
    }

    function saveLaboratoryComment(Request $request)
    {
        $header = LaboratoryHeader::where('laboratory_code', $request->laboratory_code)->first();

        if (!$header) {
            throw ValidationException::withMessages([
                'laboratory_code' => ['Laboratory transaction not found.'],
            ]);
        }

        if (!$this->canAccessLocation($header->location_id)) {
            $this->denyAccess();
        }

        if ($header->lock || !in_array((int) $header->status, [0, 1, 4], true)) {
            throw ValidationException::withMessages([
                'laboratory_code' => ['This transaction is locked.'],
            ]);
        }

        $header->update([
            'comment' => $request->comment ? trim($request->comment) : null,
        ]);

        return ResponseFormatter::success(
            ['laboratory_code' => $header->laboratory_code],
            'Laboratory comment successfully saved'
        );
    }

    function printLaboratoryPdf(Request $request)
    {
        $report = $this->buildLaboratoryReport($request->get('laboratory_code'));

        if (!$this->canViewLaboratory($report['header'])) {
            abort(403, 'You are not allowed to access this laboratory transaction');
        }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 8,
            'margin_bottom' => 8,
            'margin_left' => 8,
            'margin_right' => 8,
        ]);
        $mpdf->WriteHTML(view('report.inspection.report_laboratory', $report)->render());
        ob_clean();

        $filename = 'LIMS_Laboratory_' . str_replace('/', '_', $report['header']->laboratory_code) . '_' . date('Ymd_Hi') . '.pdf';
        $mpdf->Output($filename, 'I');
        exit;
    }

    function exportLaboratoryExcel(Request $request)
    {
        $laboratoryCode = $request->get('laboratory_code');
        $report = $this->buildLaboratoryReport($laboratoryCode);

        if (!$this->canViewLaboratory($report['header'])) {
            abort(403, 'You are not allowed to access this laboratory transaction');
        }

        $filename = 'LIMS_Laboratory_' . str_replace('/', '_', $laboratoryCode) . '_' . date('Ymd_Hi') . '.xlsx';

        return Excel::download(new LaboratoryReportExport($laboratoryCode), $filename);
    }

    /**
     * Collects everything the printed "Record of Laboratory Testing" form
     * (03/QC/12-1999 Rev-00) needs. Shared by the PDF and the Excel export so
     * the two can never drift apart.
     */
    public function buildLaboratoryReport($laboratoryCode)
    {
        if (!$laboratoryCode) {
            abort(404, 'Laboratory code required');
        }

        $header = LaboratoryHeader::with([
            'productRelation',
            'productMethodRelation.methodRelation',
            'locationRelation',
            'machineRelation',
            'userRelation',
            'detailsRelation.measurementsRelation',
            'detailsRelation.inChargeRelation',
            'detailsRelation.checkerRelation',
            'detailsRelation.configurationRelation.parameterRelation',
        ])->where('laboratory_code', $laboratoryCode)->first();

        if (!$header) {
            abort(404, 'Laboratory transaction not found');
        }

        if (!in_array((int) $header->status, [2, 3], true)) {
            abort(403, 'The report is only available once the transaction has been submitted for approval.');
        }

        $rows = $header->detailsRelation->sortBy('sequence')->values()->map(function ($detail) {
            $parameter = optional(optional($detail->configurationRelation)->parameterRelation);

            return [
                'sequence' => $detail->sequence,
                'item' => $detail->parameter_name_snapshot,
                'species' => $detail->description_snapshot ?: ($parameter->description ?: ''),
                'specification' => $this->formatReportStandard($detail),
                'unit' => $detail->unit_snapshot,
                'measurements' => $detail->measurementsRelation->pluck('value')->all(),
                'figure' => $this->formatReportFigure($detail),
                'in_charge' => optional($detail->inChargeRelation)->name,
                'checker' => optional($detail->checkerRelation)->name,
                'evaluation' => $detail->evaluation,
                'remark' => $detail->remark,
            ];
        });

        $history = ApprovalHistory::with('userRelation')
            ->where('transaction_code', $header->laboratory_code)
            ->where('inspection_type', 1)
            ->orderBy('id')
            ->get();

        // Slot tanda tangan hanya boleh diisi siklus approval TERAKHIR.
        // Tanpa ini, history siklus sebelum revisi membuat nama penandatangan
        // dobel/bergeser di report.
        $lastRevisionId = $history->where('status', 4)->max('id');
        if ($lastRevisionId) {
            $history = $history->filter(function ($item) use ($lastRevisionId) {
                return $item->id > $lastRevisionId;
            });
        }
        $history = $history->groupBy('step')
            ->map(function ($items) {
                return $items->last();
            })
            ->sortKeys()
            ->values();

        return [
            'header' => $header,
            'rows' => $rows,
            'history' => $history,
            'logo' => file_exists(public_path('icon.png')) ? public_path('icon.png') : null,
        ];
    }

    /**
     * Renders a standard the way the paper form writes it: "60 + 0.4",
     * "0,600 ~ 0,700", "450 Min", "5 Max" or a plain expected value.
     */
    private function formatReportStandard($detail)
    {
        $standard = $detail->standard_snapshot ?: [];
        $unit = $detail->unit_snapshot ? ' ' . $detail->unit_snapshot : '';

        if (isset($standard['expected_value']) && $standard['expected_value'] !== null && $standard['expected_value'] !== '') {
            return $standard['expected_value'];
        }

        $operator = $standard['operator'] ?? null;
        $round = function ($value) {
            return rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.');
        };

        if ($operator === 'BETWEEN') {
            if (!empty($standard['tolerance_plus']) && isset($standard['target_value'])) {
                return $round($standard['target_value']) . ' + ' . $round($standard['tolerance_plus']) . $unit;
            }

            return $round($standard['min_value']) . ' ~ ' . $round($standard['max_value']) . $unit;
        }

        if ($operator === '>=') {
            return $round($standard['min_value']) . $unit . ', Min';
        }

        if ($operator === '<=') {
            return $round($standard['max_value']) . $unit . ', Max';
        }

        if ($operator === '=' && isset($standard['target_value'])) {
            return $round($standard['target_value']) . $unit;
        }

        return '-';
    }

    private function formatReportFigure($detail)
    {
        if ($detail->result_type_snapshot === 'text') {
            return $detail->text_result ?: '-';
        }

        if ($detail->result_type_snapshot === 'boolean') {
            if ($detail->boolean_result === null) {
                return '-';
            }

            return $detail->boolean_result ? 'Yes' : 'No';
        }

        if ($detail->result_value === null) {
            return '-';
        }

        return rtrim(rtrim(number_format((float) $detail->result_value, 4, '.', ''), '0'), '.');
    }

    private function generateLaboratoryCode()
    {
        $month = (int) date('m');
        $year = date('y');
        $romanMonth = NumConvert::roman($month);
        $last = LaboratoryHeader::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();
        $sequence = 1;

        if ($last) {
            $parts = explode('/', $last->laboratory_code);
            $sequence = isset($parts[0]) ? ((int) $parts[0]) + 1 : 1;
        }

        return $sequence . '/LAB/' . $romanMonth . '/' . $year;
    }

    private function resolveStandard($configuration, $product, $resultType)
    {
        $snapshot = [
            'source_type' => $configuration->source_type ?: 'STATIC',
            'operator' => $configuration->operator,
            'min_field' => $configuration->source_min_field,
            'max_field' => $configuration->source_max_field,
            'target_field' => $configuration->source_target_field,
            'min_value' => $configuration->min_value !== null ? (float) $configuration->min_value : null,
            'max_value' => $configuration->max_value !== null ? (float) $configuration->max_value : null,
            'target_value' => $configuration->target_value !== null ? (float) $configuration->target_value : null,
            'tolerance_plus' => $configuration->tolerance_plus !== null ? (float) $configuration->tolerance_plus : null,
            'tolerance_minus' => $configuration->tolerance_minus !== null ? (float) $configuration->tolerance_minus : null,
            'expected_value' => $configuration->expected_value,
            'unit' => $configuration->unit,
        ];

        if ($resultType !== 'numeric') {
            $snapshot['operator'] = $configuration->expected_value !== null ? '=' : null;
            return $snapshot;
        }

        if ($snapshot['source_type'] === 'PRODUCT_FIELD') {
            foreach (['min', 'max', 'target'] as $type) {
                $fieldKey = $type . '_field';
                $valueKey = $type . '_value';
                if ($snapshot[$fieldKey]) {
                    $snapshot[$valueKey] = $this->numericProductValue($product, $snapshot[$fieldKey]);
                }
            }
        }

        if ($snapshot['target_value'] !== null && ($snapshot['tolerance_plus'] !== null || $snapshot['tolerance_minus'] !== null)) {
            $snapshot['operator'] = 'BETWEEN';
            $snapshot['min_value'] = $snapshot['target_value'] - ($snapshot['tolerance_minus'] ?: 0);
            $snapshot['max_value'] = $snapshot['target_value'] + ($snapshot['tolerance_plus'] ?: 0);
        }

        return $snapshot;
    }

    private function numericProductValue($product, $field)
    {
        $allowedFields = ['min_od', 'max_od', 'avg_od', 'min_wt', 'max_wt', 'avg_wt'];
        if (!in_array($field, $allowedFields, true)) {
            throw ValidationException::withMessages([
                'product_method_id' => ['Invalid product standard source: ' . $field . '.'],
            ]);
        }

        $value = str_replace(',', '.', trim((string) $product->{$field}));
        if (!is_numeric($value)) {
            throw ValidationException::withMessages([
                'product_method_id' => ['Product field ' . $field . ' is not numeric.'],
            ]);
        }

        return (float) $value;
    }

    private function calculateNumericResult(array $measurements, $calculationType)
    {
        $values = collect($measurements)->map(function ($value) {
            return (float) $value;
        });

        if ($calculationType === 'AVG') {
            return round($values->avg(), 6);
        }
        if ($calculationType === 'MIN') {
            return round($values->min(), 6);
        }
        if ($calculationType === 'MAX') {
            return round($values->max(), 6);
        }

        return round($values->first(), 6);
    }

    private function evaluateNumeric($value, array $standard)
    {
        $operator = $standard['operator'] ?? null;
        if (!$operator) {
            return null;
        }

        $good = null;
        if ($operator === '>=') {
            $good = $value >= ($standard['min_value'] ?? $standard['target_value']);
        } elseif ($operator === '<=') {
            $good = $value <= ($standard['max_value'] ?? $standard['target_value']);
        } elseif ($operator === '=') {
            $good = abs($value - $standard['target_value']) < 0.000001;
        } elseif ($operator === 'BETWEEN') {
            $good = $value >= $standard['min_value'] && $value <= $standard['max_value'];
        }

        return $good === null ? null : ($good ? 'GOOD' : 'NO_GOOD');
    }

    private function evaluateText($value, array $standard)
    {
        $expected = $standard['expected_value'] ?? null;
        if ($expected === null || $expected === '' || $value === null || $value === '') {
            return null;
        }

        return mb_strtolower(trim($value)) === mb_strtolower(trim($expected)) ? 'GOOD' : 'NO_GOOD';
    }

    private function evaluateBoolean($value, array $standard)
    {
        $expected = $standard['expected_value'] ?? null;
        if ($expected === null || $expected === '' || $value === null) {
            return null;
        }

        $expectedBoolean = in_array(mb_strtolower(trim((string) $expected)), ['1', 'true', 'yes', 'good', 'pass'], true);
        return (bool) $value === $expectedBoolean ? 'GOOD' : 'NO_GOOD';
    }
}
