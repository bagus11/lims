<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryTestMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    function productMethodsRelation()
    {
        return $this->hasMany(LaboratoryProductMethod::class, 'test_method_id', 'id');
    }
}
