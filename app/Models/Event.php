<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];



    
    public function dates()
    {
        return $this->hasMany(EventDate::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }
}
