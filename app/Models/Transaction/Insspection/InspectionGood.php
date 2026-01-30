<?php

namespace App\Models\Transaction\Insspection;

use App\Models\Master\Machine;
use App\Models\Master\MasterLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionGood extends Model
{
    use HasFactory;
    protected $guarded = [];
    function machineRelation(){
        return $this->hasOne(Machine::class,'id','machine_id');
    }
    function locationRelation(){
        return $this->hasOne(MasterLocation::class,'id','location_id');
    }

}
