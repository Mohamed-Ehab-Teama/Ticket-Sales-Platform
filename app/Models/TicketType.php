<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $guarded = ['id'];



    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
