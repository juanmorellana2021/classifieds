<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'listing_type',
        'title',
        'description',
        'price',
        'currency',
        'location_city',
        'location_region',
        'peru_province_id',
        'latitude',
        'longitude',
        'location_source',
        'location_detected_at',
        'contact_phone',
        'contact_email',
        'job_company',
        'job_employment_type',
        'job_salary_min',
        'job_salary_max',
        'job_is_remote',
        'property_listing_type',
        'property_bedrooms',
        'property_bathrooms',
        'property_area_m2',
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_mileage_km',
        'vehicle_transmission',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'job_salary_min' => 'decimal:2',
            'job_salary_max' => 'decimal:2',
            'job_is_remote' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'location_detected_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class);
    }

    public function peruProvince(): BelongsTo
    {
        return $this->belongsTo(PeruProvince::class);
    }
}
