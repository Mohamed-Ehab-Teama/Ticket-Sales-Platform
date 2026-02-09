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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id')->constrained('ticket_types', 'id')
                ->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('time_slots', 'id')
                ->cascadeOnDelete();
            $table->integer('total_quantity');
            $table->integer('sold_quantity')->default(0);
            $table->timestamps();

            $table->unique(['ticket_type_id', 'time_slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
