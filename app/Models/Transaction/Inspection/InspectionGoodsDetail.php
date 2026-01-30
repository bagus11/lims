<?php

namespace App\Models\Transaction\Inspection;

use App\Models\Transaction\Insspection\InspectionGood;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionGoodsDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    function headerRelation() {
        return $this->hasOne(InspectionGood::class,'inspection_code','inspection_code');
    }
    function subdetailRelation() {
        return $this->hasMany(InspectionGoodsSubDetail::class,'inspection_detail','detail_inspection_code');
    }
   
}
