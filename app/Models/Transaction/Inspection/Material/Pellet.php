<?php

namespace App\Models\Transaction\Inspection\Material;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pellet extends Model
{
    use HasFactory;
    protected $guarded = [];

    function produsenRelation(){
        return $this->hasOne(Supplier::class, 'id', 'produsen_id');
    }
}
