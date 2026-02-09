<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{

    public function checkIn(User $user, Ticket $ticket)
    {
        return in_array($user->role, ['admin', 'staff'])
            && is_null($ticket->checked_in_at);
    }

    public function view(User $user, Ticket $ticket)
    {
        return $user->role === 'admin'
            || $ticket->orderItem->order->user_id === $user->id;
    }
}
