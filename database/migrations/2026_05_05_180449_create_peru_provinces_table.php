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
        Schema::create('peru_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('ubigeo', 4)->unique();
            $table->string('department', 100);
            $table->string('province', 120);
            $table->timestamps();

            $table->index(['department', 'province']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peru_provinces');
    }
};
