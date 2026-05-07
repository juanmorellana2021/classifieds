<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class PeruProvince extends Model
{
    protected $fillable = [
        'ubigeo',
        'department',
        'province',
    ];

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
