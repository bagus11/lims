<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryProductMethodParameter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_required' => 'boolean',
        'measurement_count' => 'integer',
        'sequence' => 'integer',
    ];

    function productMethodRelation()
    {
        return $this->hasOne(LaboratoryProductMethod::class, 'id', 'product_method_id');
    }

    function parameterRelation()
    {
        return $this->hasOne(LaboratoryTestParameter::class, 'id', 'test_parameter_id');
    }
}
