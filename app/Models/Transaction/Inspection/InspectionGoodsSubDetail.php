<?php

namespace App\Models\Transaction\Inspection;

use App\Models\Master\Machine;
use App\Models\Master\MasterProduct;
use App\Models\Master\Material;
use App\Models\Transaction\Goods\WallThickness;
use App\Models\Transaction\Insspection\InspectionGood;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionGoodsSubDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    function machineRelation() {
        return $this->hasOne(Machine::class,'id','machine_id');
    }
    function productRelation() {
        return $this->hasOne(MasterProduct::class,'id','product_id');
    }
    function materialRelation() {
        return $this->hasOne(Material::class,'id','material_id');
    }
    function userRelation(){
        return $this->hasOne(User::class,'id','user_id');
    }
    function inspectorRelation(){
        return $this->hasOne(User::class,'id','inspector');
    }
    function wallThicknessRelation() {
        return $this->hasMany(WallThickness::class,'sub_detail_inspection_code','inspection_sub_detail');
    }
    function headerRelation(){
        return $this->hasOne(InspectionGood::class,'inspection_code','inspection_header');
    }
    function detailRelation(){
        return $this->hasOne(InspectionGoodsDetail::class,'detail_inspection_code','inspection_detail');
    }
}
