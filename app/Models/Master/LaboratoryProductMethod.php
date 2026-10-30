<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryProductMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    function productRelation()
    {
        return $this->hasOne(MasterProduct::class, 'id', 'product_id');
    }

    function methodRelation()
    {
        return $this->hasOne(LaboratoryTestMethod::class, 'id', 'test_method_id');
    }

    function parametersRelation()
    {
        return $this->hasMany(LaboratoryProductMethodParameter::class, 'product_method_id', 'id')->orderBy('sequence');
    }
}
