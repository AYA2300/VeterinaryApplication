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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constraiend('carts')->onDelete('cascade');
            $table->enum('delivery_type',['delivery','non_delivery'])->default('non_delivery');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending','Processing', 'completed', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
