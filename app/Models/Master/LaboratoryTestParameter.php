<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryTestParameter extends Model
{
    use HasFactory;

    protected $guarded = [];

    function configurationsRelation()
    {
        return $this->hasMany(LaboratoryProductMethodParameter::class, 'test_parameter_id', 'id');
    }
}
