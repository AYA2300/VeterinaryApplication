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
        Schema::create('animal_categorie_breeder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeder_id')->constrained('breeders')->onDelete('cascade');
            $table->foreignId('animal_categorie_id')->constrained('animal_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_categorie_breeder');
    }
};
