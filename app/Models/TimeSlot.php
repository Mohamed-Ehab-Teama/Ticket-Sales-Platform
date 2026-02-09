<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $guarded = ['id'];



    public function date()
    {
        return $this->belongsTo(EventDate::class, 'event_date_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'time_slot_id');
    }
}
