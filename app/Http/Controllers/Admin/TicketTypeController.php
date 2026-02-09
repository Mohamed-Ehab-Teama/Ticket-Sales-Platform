<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketTypeController extends Controller
{
    //
    public function index(Event $event)
    {
        $ticketTypes = $event->ticketTypes()->latest()->get();
        return view('admin.ticket_types.index', compact('event', 'ticketTypes'));
    }



    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'min_per_order' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|gte:min_per_order',
            'valid_from'    => 'nullable|date',
            'valid_to'      => 'nullable|date|after_or_equal:valid_from',
        ]);

        $event->ticketTypes()->create($data);

        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type created successfully!');
    }


    public function edit(TicketType $ticketType)
    {
        return response()->json($ticketType);
    }


    public function update(Request $request, Event $event, TicketType $ticketType)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'min_per_order' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|gte:min_per_order',
            'valid_from'    => 'nullable|date',
            'valid_to'      => 'nullable|date|after_or_equal:valid_from',
        ]);

        $ticketType->update($data);

        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type updated successfully!');
    }


    public function destroy(Event $event, TicketType $ticketType)
    {
        $ticketType->delete();
        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type deleted successfully!');
    }
    // 
}
