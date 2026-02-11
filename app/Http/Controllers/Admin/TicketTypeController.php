<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;

class TicketTypeController extends Controller
{
    //
    public function index(Event $event)
    {
        $ticketTypes = $event->ticketTypes()->latest()->paginate(15);
        return view('admin.ticket-types.index', compact('event', 'ticketTypes'));
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

        DB::transaction(function () use ($data, $event) {
            // 
            $ticketType = $event->ticketTypes()->create($data);

            InventoryService::createForTicketType($ticketType);

            return $ticketType;
        });

        return response()->json([
            'message'   => 'Ticket type created successfully!',
            'data'      => $event,
        ], 201);
    }


    public function edit(Event $event, TicketType $ticketType)
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

        return response()->json([
            'message'   => 'Ticket type updated successfully!',
            'data'      => $event,
        ], 201);
    }


    public function destroy(Event $event, TicketType $ticketType)
    {
        $ticketType->delete();
        return response()->json([
            'message'   => 'Ticket type deleted successfully!',
            'data'      => $event,
        ], 201);
    }
    // 
}
