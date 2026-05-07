<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('property_listing_type', 20)->nullable()->after('listing_type');
            $table->unsignedSmallInteger('property_bedrooms')->nullable()->after('property_listing_type');
            $table->unsignedSmallInteger('property_bathrooms')->nullable()->after('property_bedrooms');
            $table->unsignedInteger('property_area_m2')->nullable()->after('property_bathrooms');

            $table->string('vehicle_type', 30)->nullable()->after('property_area_m2');
            $table->string('vehicle_make', 80)->nullable()->after('vehicle_type');
            $table->string('vehicle_model', 80)->nullable()->after('vehicle_make');
            $table->unsignedSmallInteger('vehicle_year')->nullable()->after('vehicle_model');
            $table->unsignedBigInteger('vehicle_mileage_km')->nullable()->after('vehicle_year');
            $table->string('vehicle_transmission', 30)->nullable()->after('vehicle_mileage_km');

            $table->index(['property_listing_type', 'status']);
            $table->index(['vehicle_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
