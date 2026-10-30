<?php

namespace App\Models\Transaction\Inspection\Laboratory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryMeasurement extends Model
{
    use HasFactory;

    protected $guarded = [];

    function detailRelation()
    {
        return $this->hasOne(LaboratoryDetail::class, 'id', 'laboratory_detail_id');
    }
}
