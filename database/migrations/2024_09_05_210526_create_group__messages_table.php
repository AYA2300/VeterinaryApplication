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
        Schema::create('group__messages', function (Blueprint $table) {
            $table->id();
            $table->LongText('message');
            $table->string('type')->default('text');
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->foreignId('breeder_id')->constrained()->onDelete('cascade'); // يمكن أن تكون 'text', 'audio', 'image'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group__messages');
    }
};
