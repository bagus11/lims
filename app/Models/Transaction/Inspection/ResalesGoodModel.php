<?php

namespace App\Models\Transaction\Inspection;

use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResalesGoodModel extends Model
{
    use HasFactory;
      protected $guarded = [];

    function locationRelation() {
        return $this->hasOne(MasterLocation::class,'id','location');
    }
    function userRelation(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    function productRelation(){
        return $this->hasOne(MasterProduct::class,'id', 'product_id');
    }
}
