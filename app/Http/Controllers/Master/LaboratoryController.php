<?php

namespace App\Http\Controllers\Master;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Laboratory\SaveLaboratoryMethodParametersRequest;
use App\Http\Requests\Master\Laboratory\SaveLaboratoryProductMethodRequest;
use App\Http\Requests\Master\Laboratory\StoreLaboratoryTestMethodRequest;
use App\Http\Requests\Master\Laboratory\StoreLaboratoryTestParameterRequest;
use App\Http\Requests\Master\Laboratory\UpdateLaboratoryTestMethodRequest;
use App\Http\Requests\Master\Laboratory\UpdateLaboratoryTestParameterRequest;
use App\Models\Master\LaboratoryProductMethod;
use App\Models\Master\LaboratoryProductMethodParameter;
use App\Models\Master\LaboratoryTestMethod;
use App\Models\Master\LaboratoryTestParameter;
use App\Models\Master\MasterProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LaboratoryController extends Controller
{
    function index()
    {
        return view('master.laboratory.laboratory-index');
    }

    function getLaboratoryMasterOptions()
    {
        return response()->json([
            'data' => [
                'products' => MasterProduct::orderBy('seri')->orderBy('ukuran')->get(),
                'methods' => LaboratoryTestMethod::where('status', 1)->orderBy('code')->get(),
                'parameters' => LaboratoryTestParameter::where('status', 1)->orderBy('name')->get(),
            ],
        ]);
    }

    function getLaboratoryTestMethods()
    {
        return response()->json([
            'data' => LaboratoryTestMethod::orderBy('code')->get(),
        ]);
    }

    function addLaboratoryTestMethod(Request $request, StoreLaboratoryTestMethodRequest $storeRequest)
    {
        $storeRequest->validated();

        $post = [
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
            'description' => $request->description ? trim($request->description) : null,
            'status' => 1,
            'user_id' => auth()->user()->id,
        ];

        DB::transaction(function () use ($post) {
            LaboratoryTestMethod::create($post);
        });

        return ResponseFormatter::success($post, 'Laboratory test method successfully added');
    }

    function updateLaboratoryTestMethod(Request $request, UpdateLaboratoryTestMethodRequest $updateRequest)
    {
        $updateRequest->validated();

        $post = [
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
            'description' => $request->description ? trim($request->description) : null,
            'status' => (int) $request->status,
            'user_id' => auth()->user()->id,
        ];

        DB::transaction(function () use ($post, $request) {
            LaboratoryTestMethod::findOrFail($request->id)->update($post);
        });

        return ResponseFormatter::success($post, 'Laboratory test method successfully updated');
    }

    function getLaboratoryTestParameters()
    {
        return response()->json([
            'data' => LaboratoryTestParameter::orderBy('name')->get(),
        ]);
    }

    function addLaboratoryTestParameter(Request $request, StoreLaboratoryTestParameterRequest $storeRequest)
    {
        $storeRequest->validated();

        $post = [
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
            'result_type' => $request->result_type,
            'default_unit' => $request->default_unit ? trim($request->default_unit) : null,
            'description' => $request->description ? trim($request->description) : null,
            'status' => 1,
            'user_id' => auth()->user()->id,
        ];

        DB::transaction(function () use ($post) {
            LaboratoryTestParameter::create($post);
        });

        return ResponseFormatter::success($post, 'Laboratory test parameter successfully added');
    }

    function updateLaboratoryTestParameter(Request $request, UpdateLaboratoryTestParameterRequest $updateRequest)
    {
        $updateRequest->validated();

        $post = [
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
            'result_type' => $request->result_type,
            'default_unit' => $request->default_unit ? trim($request->default_unit) : null,
            'description' => $request->description ? trim($request->description) : null,
            'status' => (int) $request->status,
            'user_id' => auth()->user()->id,
        ];

        DB::transaction(function () use ($post, $request) {
            LaboratoryTestParameter::findOrFail($request->id)->update($post);
        });

        return ResponseFormatter::success($post, 'Laboratory test parameter successfully updated');
    }

    function getLaboratoryProductMethods(Request $request)
    {
        $query = LaboratoryProductMethod::with([
            'productRelation',
            'methodRelation',
            'parametersRelation.parameterRelation',
        ]);

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json([
            'data' => $query->orderBy('product_id')->orderBy('test_method_id')->get(),
        ]);
    }

    function saveLaboratoryProductMethods(Request $request, SaveLaboratoryProductMethodRequest $saveRequest)
    {
        $saveRequest->validated();
        $methodIds = collect($request->test_method_ids)->map(function ($id) {
            return (int) $id;
        })->unique()->values();

        DB::transaction(function () use ($request, $methodIds) {
            LaboratoryProductMethod::where('product_id', $request->product_id)
                ->whereNotIn('test_method_id', $methodIds)
                ->update(['status' => 0, 'user_id' => auth()->user()->id]);

            foreach ($methodIds as $methodId) {
                LaboratoryProductMethod::updateOrCreate(
                    [
                        'product_id' => $request->product_id,
                        'test_method_id' => $methodId,
                    ],
                    [
                        'status' => 1,
                        'user_id' => auth()->user()->id,
                    ]
                );
            }
        });

        return ResponseFormatter::success(
            ['product_id' => (int) $request->product_id, 'test_method_ids' => $methodIds],
            'Product laboratory methods successfully saved'
        );
    }

    function getLaboratoryMethodParameters(Request $request)
    {
        $data = LaboratoryProductMethodParameter::with('parameterRelation')
            ->where('product_method_id', $request->product_method_id)
            ->orderBy('sequence')
            ->get();

        return response()->json(['data' => $data]);
    }

    function saveLaboratoryMethodParameters(Request $request, SaveLaboratoryMethodParametersRequest $saveRequest)
    {
        $saveRequest->validated();
        $parameters = collect($request->parameters)->values();

        $duplicateSequence = $parameters->pluck('sequence')->count() !== $parameters->pluck('sequence')->unique()->count();
        if ($duplicateSequence) {
            throw ValidationException::withMessages([
                'parameters' => ['Parameter sequence must be unique.'],
            ]);
        }

        $parameterModels = LaboratoryTestParameter::whereIn('id', $parameters->pluck('test_parameter_id'))
            ->get()
            ->keyBy('id');

        foreach ($parameters as $index => $configuration) {
            $parameter = $parameterModels->get((int) $configuration['test_parameter_id']);
            $this->validateConfiguration($configuration, $parameter, $index);
        }

        DB::transaction(function () use ($request, $parameters, $parameterModels) {
            $selectedIds = $parameters->pluck('test_parameter_id')->map(function ($id) {
                return (int) $id;
            });

            LaboratoryProductMethodParameter::where('product_method_id', $request->product_method_id)
                ->whereNotIn('test_parameter_id', $selectedIds)
                ->update(['status' => 0, 'user_id' => auth()->user()->id]);

            foreach ($parameters as $configuration) {
                $parameter = $parameterModels->get((int) $configuration['test_parameter_id']);
                $post = [
                    'sequence' => (int) $configuration['sequence'],
                    'source_type' => !empty($configuration['source_type']) ? $configuration['source_type'] : 'STATIC',
                    'source_min_field' => !empty($configuration['source_min_field']) ? $configuration['source_min_field'] : null,
                    'source_max_field' => !empty($configuration['source_max_field']) ? $configuration['source_max_field'] : null,
                    'source_target_field' => !empty($configuration['source_target_field']) ? $configuration['source_target_field'] : null,
                    'operator' => !empty($configuration['operator']) ? $configuration['operator'] : null,
                    'min_value' => isset($configuration['min_value']) && $configuration['min_value'] !== '' ? $configuration['min_value'] : null,
                    'max_value' => isset($configuration['max_value']) && $configuration['max_value'] !== '' ? $configuration['max_value'] : null,
                    'target_value' => isset($configuration['target_value']) && $configuration['target_value'] !== '' ? $configuration['target_value'] : null,
                    'tolerance_plus' => isset($configuration['tolerance_plus']) && $configuration['tolerance_plus'] !== '' ? $configuration['tolerance_plus'] : null,
                    'tolerance_minus' => isset($configuration['tolerance_minus']) && $configuration['tolerance_minus'] !== '' ? $configuration['tolerance_minus'] : null,
                    'expected_value' => isset($configuration['expected_value']) && $configuration['expected_value'] !== '' ? trim($configuration['expected_value']) : null,
                    'unit' => isset($configuration['unit']) && $configuration['unit'] !== '' ? trim($configuration['unit']) : $parameter->default_unit,
                    'measurement_count' => $parameter->result_type === 'numeric' ? (int) (!empty($configuration['measurement_count']) ? $configuration['measurement_count'] : 1) : null,
                    'calculation_type' => $parameter->result_type === 'numeric' ? ($configuration['calculation_type'] ?? 'NONE') : 'NONE',
                    'is_required' => (int) $configuration['is_required'],
                    'status' => array_key_exists('status', $configuration) ? (int) $configuration['status'] : 1,
                    'user_id' => auth()->user()->id,
                ];

                LaboratoryProductMethodParameter::updateOrCreate(
                    [
                        'product_method_id' => $request->product_method_id,
                        'test_parameter_id' => $configuration['test_parameter_id'],
                    ],
                    $post
                );
            }
        });

        return ResponseFormatter::success(
            ['product_method_id' => (int) $request->product_method_id],
            'Laboratory method parameters successfully saved'
        );
    }

    private function validateConfiguration(array $configuration, $parameter, $index)
    {
        if (!$parameter) {
            throw ValidationException::withMessages([
                'parameters.' . $index . '.test_parameter_id' => ['Test parameter not found.'],
            ]);
        }

        if ($parameter->result_type !== 'numeric') {
            return;
        }

        if (empty($configuration['source_type']) || empty($configuration['operator'])) {
            throw ValidationException::withMessages([
                'parameters.' . $index . '.source_type' => ['Numeric parameter requires source type and operator.'],
            ]);
        }

        $measurementCount = (int) ($configuration['measurement_count'] ?? 1);
        if (($configuration['calculation_type'] ?? 'NONE') === 'NONE' && $measurementCount !== 1) {
            throw ValidationException::withMessages([
                'parameters.' . $index . '.measurement_count' => ['Calculation NONE requires exactly one measurement.'],
            ]);
        }

        $sourceType = $configuration['source_type'];
        $operator = $configuration['operator'];
        $prefix = $sourceType === 'PRODUCT_FIELD' ? 'source_' : '';
        $suffix = $sourceType === 'PRODUCT_FIELD' ? '_field' : '_value';

        if ($operator === 'BETWEEN') {
            $required = [$prefix . 'min' . $suffix, $prefix . 'max' . $suffix];
        } elseif ($operator === '>=') {
            $required = [$prefix . 'min' . $suffix];
        } elseif ($operator === '<=') {
            $required = [$prefix . 'max' . $suffix];
        } else {
            $required = [$prefix . 'target' . $suffix];
        }

        foreach ($required as $field) {
            if (!array_key_exists($field, $configuration) || $configuration[$field] === null || $configuration[$field] === '') {
                throw ValidationException::withMessages([
                    'parameters.' . $index . '.' . $field => ['This standard value is required for the selected operator.'],
                ]);
            }
        }
    }
}
