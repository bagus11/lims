<?php

namespace App\Models\Transaction\Inspection\Laboratory;

use App\Models\Master\LaboratoryProductMethodParameter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'standard_snapshot' => 'array',
        'boolean_result' => 'boolean',
        'is_required_snapshot' => 'boolean',
        'measurement_count_snapshot' => 'integer',
    ];

    function headerRelation()
    {
        return $this->hasOne(LaboratoryHeader::class, 'id', 'laboratory_header_id');
    }

    function configurationRelation()
    {
        return $this->hasOne(LaboratoryProductMethodParameter::class, 'id', 'product_method_parameter_id');
    }

    function inChargeRelation()
    {
        return $this->hasOne(User::class, 'id', 'in_charge_user_id');
    }

    function checkerRelation()
    {
        return $this->hasOne(User::class, 'id', 'checker_user_id');
    }

    function measurementsRelation()
    {
        return $this->hasMany(LaboratoryMeasurement::class, 'laboratory_detail_id', 'id')->orderBy('sequence');
    }
}
