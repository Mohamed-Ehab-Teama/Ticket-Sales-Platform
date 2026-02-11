<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded = ['id'];



    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function reservations()
    {
        return $this->hasMany(InventoryReservation::class, 'inventory_id');
    }



    // Accessors
    public function getAvailableAttribute() 
    {
        return ($this->total_quantity - $this->sold_quantity) ?? 0;
    }

    public function getAvailableQuantityAttribute()
    {
        $reserved = $this->reservations()
            ->where('expires_at', '>', now())
            ->sum('quantity');

        return max(
            $this->total_quantity - $this->sold_quantity - $reserved,
            0
        );
    }
}
