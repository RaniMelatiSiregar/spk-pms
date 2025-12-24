<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_id',
        'code',
        'name',
        'type',
        'weight',
        'slug'
    ];

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($criteria) {
            $criteria->slug = str()->slug($criteria->name);
        });

        static::updating(function ($criteria) {
            $criteria->slug = str()->slug($criteria->name);
        });
    }

    public function scores() {
    return $this->hasMany(SupplierScore::class);
    }

}
