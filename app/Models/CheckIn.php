<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{

    protected $guarded = ['id'];



    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
