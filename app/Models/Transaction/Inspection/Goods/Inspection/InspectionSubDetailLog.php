<?php

namespace App\Models\Transaction\Inspection\Goods\Inspection;

use App\Models\Master\Machine;
use App\Models\Master\MasterProduct;
use App\Models\Master\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionSubDetailLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    function creatorRelation() {
        return $this->hasOne(User::class,'id', 'user_id');
    }
    function inspectorRelation() {
        return $this->hasOne(User::class,'id', 'inspector');
    }
    function machineRelation() {
        return $this->hasOne(Machine::class,'id','machine_id');
    }
    function productRelation() {
        return $this->hasOne(MasterProduct::class,'id','product_id');
    }
    function materialRelation() {
        return $this->hasOne(Material::class,'id','material_id');
    }
}
