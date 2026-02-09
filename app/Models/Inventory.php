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



    
    public function getAvailableAttribute()
    {
        return $this->total_quantity - $this->sold_quantity;
    }
}
