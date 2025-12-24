<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierScore extends Model
{
    protected $fillable = [
        'supplier_id',
        'criteria_id',
        'parameter_id',
        'raw_value',
        'score',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function criteria() {
        return $this->belongsTo(Criteria::class);
    }

    public function parameter() {
        return $this->belongsTo(Parameter::class);
    }

    public function scores() {
    return $this->hasMany(SupplierScore::class);
    }   

}
