<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    protected $guarded = ['id'];



    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
