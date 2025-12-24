<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'criteria_id',
        'score',
        'min_value',
        'max_value'
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
