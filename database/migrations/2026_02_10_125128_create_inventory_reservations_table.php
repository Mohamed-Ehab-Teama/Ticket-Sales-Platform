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
        Schema::create('inventory_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_id')->constrained('inventories', 'id')->cascadeOnDelete();
            $table->foreignId('cart_id')->constrained('carts', 'id')->cascadeOnDelete();

            $table->integer('quantity');
            $table->timestamp('expires_at');

            $table->timestamps();

            $table->index(['inventory_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_reservations');
    }
};
