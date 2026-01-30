<?php

namespace App\Models\Transaction\Inspection\Material;

use App\Models\Transaction\Material\InspectionMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResinDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    function inspectionRelation() {
        return $this->hasOne(InspectionMaterial::class,'inspection_code','inspection_code');
    }
}
