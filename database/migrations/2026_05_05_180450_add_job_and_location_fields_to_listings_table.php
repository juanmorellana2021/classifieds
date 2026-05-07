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
            $table->string('listing_type', 20)->default('item')->after('user_id');
            $table->foreignId('peru_province_id')->nullable()->after('location_region')
                ->constrained('peru_provinces')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable()->after('peru_province_id');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('location_source', 30)->nullable()->after('longitude');
            $table->timestamp('location_detected_at')->nullable()->after('location_source');

            $table->string('job_company', 180)->nullable()->after('contact_email');
            $table->string('job_employment_type', 60)->nullable()->after('job_company');
            $table->decimal('job_salary_min', 12, 2)->nullable()->after('job_employment_type');
            $table->decimal('job_salary_max', 12, 2)->nullable()->after('job_salary_min');
            $table->boolean('job_is_remote')->default(false)->after('job_salary_max');

            $table->index(['listing_type', 'status']);
            $table->index(['peru_province_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['peru_province_id']);
            $table->dropColumn([
                'listing_type',
                'peru_province_id',
                'latitude',
                'longitude',
                'location_source',
                'location_detected_at',
                'job_company',
                'job_employment_type',
                'job_salary_min',
                'job_salary_max',
                'job_is_remote',
            ]);
        });
    }
};
