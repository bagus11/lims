<?php

namespace App\Models\Transaction\Inspection\Laboratory;

use App\Models\Master\LaboratoryProductMethod;
use App\Models\Master\Machine;
use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryHeader extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'test_date' => 'date:Y-m-d',
        'specification_snapshot' => 'array',
        'method_snapshot' => 'array',
        'lock' => 'boolean',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    function productRelation()
    {
        return $this->hasOne(MasterProduct::class, 'id', 'product_id');
    }

    function productMethodRelation()
    {
        return $this->hasOne(LaboratoryProductMethod::class, 'id', 'product_method_id');
    }

    function locationRelation()
    {
        return $this->hasOne(MasterLocation::class, 'id', 'location_id');
    }

    function machineRelation()
    {
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }

    function userRelation()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function approvalUserRelation()
    {
        return $this->hasOne(User::class, 'id', 'approval_id');
    }

    function detailsRelation()
    {
        return $this->hasMany(LaboratoryDetail::class, 'laboratory_header_id', 'id')->orderBy('sequence');
    }
}
