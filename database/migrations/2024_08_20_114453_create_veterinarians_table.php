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
        Schema::create('veterinarians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('university');
            $table->year('graduation_year');
            $table->string('profile_picture')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->string('experience_certificate')->nullable();
            $table->enum('role',['veterinarian']);
            $table->rememberToken();
            $table->string('email')->unique();
            $table->string('password');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinarians');
    }
};
