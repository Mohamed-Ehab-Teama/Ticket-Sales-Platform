<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];


    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }
}
