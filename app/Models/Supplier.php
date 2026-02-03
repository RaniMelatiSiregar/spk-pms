<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'periode_id',
        'code',
        'name',
        'location',
        'price_per_kg',
        'volume_per_month',
        'on_time_percent',
        'freq_per_month',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
