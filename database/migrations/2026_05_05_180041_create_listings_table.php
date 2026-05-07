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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 8)->default('PEN');
            $table->string('location_city', 120);
            $table->string('location_region', 120)->nullable();
            $table->string('contact_phone', 30);
            $table->string('contact_email');
            $table->string('status', 20)->default('active');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['location_city', 'location_region']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
