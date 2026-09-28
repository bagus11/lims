<?php

namespace App\Models\Transaction\Inspection;

use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProductResalesGood;
use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResalesGood extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'resales_goods';

    function locationRelation() {
        return $this->hasOne(MasterLocation::class, 'id', 'location_id');
    }
    function productRelation() {
        return $this->hasOne(MasterProductResalesGood::class, 'id', 'product_id');
    }
    function supplierRelation() {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    function produsenRelation() {
        return $this->hasOne(Supplier::class, 'id', 'produsen_id');
    }
    function inspectorRelation() {
        return $this->hasOne(User::class, 'id', 'inspector_id');
    }
    function createdByRelation() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    function detailsRelation() {
        return $this->hasMany(ResalesGoodDetail::class, 'transaction_code', 'transaction_code');
    }
}
