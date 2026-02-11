<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class VerifyTicketController extends Controller
{
    public function verify($code)
    {
        $ticket = Ticket::where('code', $code)->first();

        if (!$ticket) {
            return response()->json(['status' => 'invalid']);
        }

        if ($ticket->checked_in_at) {
            return response()->json(['status' => 'already_used']);
        }

        return response()->json([
            'status' => 'valid',
            'ticket' => $ticket
        ]);
    }
}
