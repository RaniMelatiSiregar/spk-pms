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
        'operator',
        'min_value',
        'max_value',
        'description'
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
