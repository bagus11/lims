<?php

namespace Tests\Unit;

use App\Http\Controllers\Transaction\Inspection\LaboratoryController;
use App\Models\Master\MasterProduct;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

class LaboratoryRuleEngineTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LaboratoryController();
    }

    public function test_numeric_measurements_support_all_calculation_types()
    {
        $measurements = [60.10, 60.00, 60.00, 60.10, 60.10, 60.00, 60.00, 60.10];

        $this->assertEquals(60.05, $this->invoke('calculateNumericResult', [$measurements, 'AVG']));
        $this->assertEquals(60.00, $this->invoke('calculateNumericResult', [$measurements, 'MIN']));
        $this->assertEquals(60.10, $this->invoke('calculateNumericResult', [$measurements, 'MAX']));
        $this->assertEquals(60.10, $this->invoke('calculateNumericResult', [$measurements, 'NONE']));
    }

    public function test_product_field_standard_is_resolved_into_a_historical_snapshot()
    {
        $product = new MasterProduct([
            'min_od' => '60,00',
            'max_od' => '60.40',
        ]);
        $configuration = $this->configuration([
            'source_type' => 'PRODUCT_FIELD',
            'operator' => 'BETWEEN',
            'source_min_field' => 'min_od',
            'source_max_field' => 'max_od',
            'unit' => 'mm',
        ]);

        $snapshot = $this->invoke('resolveStandard', [$configuration, $product, 'numeric']);
        $product->min_od = '99';
        $product->max_od = '100';

        $this->assertSame(60.0, $snapshot['min_value']);
        $this->assertSame(60.4, $snapshot['max_value']);
        $this->assertSame('min_od', $snapshot['min_field']);
        $this->assertSame('max_od', $snapshot['max_field']);
        $this->assertSame('mm', $snapshot['unit']);
    }

    public function test_target_tolerance_is_resolved_to_between_bounds()
    {
        $configuration = $this->configuration([
            'source_type' => 'STATIC',
            'operator' => '=',
            'target_value' => 10,
            'tolerance_plus' => 0.5,
            'tolerance_minus' => 0.25,
        ]);

        $snapshot = $this->invoke('resolveStandard', [$configuration, new MasterProduct(), 'numeric']);

        $this->assertSame('BETWEEN', $snapshot['operator']);
        $this->assertSame(9.75, $snapshot['min_value']);
        $this->assertSame(10.5, $snapshot['max_value']);
    }

    public function test_numeric_text_and_boolean_rules_are_evaluated()
    {
        $this->assertSame('GOOD', $this->invoke('evaluateNumeric', [450, ['operator' => '>=', 'min_value' => 450]]));
        $this->assertSame('NO_GOOD', $this->invoke('evaluateNumeric', [449.99, ['operator' => '>=', 'min_value' => 450]]));
        $this->assertSame('GOOD', $this->invoke('evaluateNumeric', [10, ['operator' => '=', 'target_value' => 10]]));
        $this->assertSame('GOOD', $this->invoke('evaluateNumeric', [5, ['operator' => 'BETWEEN', 'min_value' => 1, 'max_value' => 5]]));
        $this->assertSame('GOOD', $this->invoke('evaluateText', ['  Smooth ', ['expected_value' => 'smooth']]));
        $this->assertSame('NO_GOOD', $this->invoke('evaluateText', ['rough', ['expected_value' => 'smooth']]));
        $this->assertSame('GOOD', $this->invoke('evaluateBoolean', [true, ['expected_value' => 'pass']]));
        $this->assertSame('NO_GOOD', $this->invoke('evaluateBoolean', [false, ['expected_value' => 'yes']]));
        $this->assertNull($this->invoke('evaluateNumeric', [10, []]));
    }

    public function test_unknown_product_field_is_rejected()
    {
        $this->expectException(ValidationException::class);

        $configuration = $this->configuration([
            'source_type' => 'PRODUCT_FIELD',
            'operator' => '=',
            'source_target_field' => 'formula',
        ]);

        $this->invoke('resolveStandard', [$configuration, new MasterProduct(['formula' => '10']), 'numeric']);
    }

    private function configuration(array $overrides)
    {
        return (object) array_merge([
            'source_type' => 'STATIC',
            'operator' => null,
            'source_min_field' => null,
            'source_max_field' => null,
            'source_target_field' => null,
            'min_value' => null,
            'max_value' => null,
            'target_value' => null,
            'tolerance_plus' => null,
            'tolerance_minus' => null,
            'expected_value' => null,
            'unit' => null,
        ], $overrides);
    }

    private function invoke($method, array $arguments)
    {
        $reflection = new ReflectionMethod(LaboratoryController::class, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($this->controller, $arguments);
    }
}
