<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date',
        'description',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean'
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }
}
