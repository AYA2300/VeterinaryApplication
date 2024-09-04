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
        Schema::create('itemables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constraiend('carts')->onDelete('cascade');
            $table->morphs('itemable');//return itemable_type,itemable_id
            $table->integer('quantity')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartables');
    }
};
