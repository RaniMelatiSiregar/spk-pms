<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_id',
        'code',
        'name',
        'location',
        'price_per_kg',
        'volume_per_month',
        'on_time_percent',
        'freq_per_month'
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function scopeActivePeriode($q)
    {
        return $q->whereHas('periode', function ($p) {
            $p->where('is_active', 1);
        });
    }
}
