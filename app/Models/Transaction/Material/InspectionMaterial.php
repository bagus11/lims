<?php

namespace App\Models\Transaction\Material;

use App\Models\Master\MasterLocation;
use App\Models\Master\Material;
use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionMaterial extends Model
{
    use HasFactory;
    protected $guarded = [];
    function locationRelation(){
        return $this->hasOne(MasterLocation::class,'id', 'location');
    }
    function materialRelation(){
        return $this->hasOne(Material::class, 'id','product_id');
    }
    function inspectorRelation(){
        return $this->hasOne(User::class,'id', 'inspector');
    }
    function userRelation() {
        return $this->hasOne(User::class,'id','user_id');
    }
    function supplierRelation() {
        return $this->hasOne(Supplier::class,'id','supplier');
    }
}
