<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::all();

        $query = Inventory::query()
            ->with(['ticketType.event', 'timeSlot.date']); // eager load relationships

        // Optional: filter by event
        if ($request->filled('event_id')) {
            $query->whereHas('ticketType.event', function ($q) use ($request) {
                $q->where('id', $request->event_id);
            });
        }

        // Optional: search by ticket type name
        if ($request->filled('ticket_name')) {
            $query->whereHas('ticketType', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->ticket_name . '%');
            });
        }

        $inventories = $query->orderBy('id', 'desc')
            ->paginate(3)
            ->withQueryString();

        return view('admin.inventories.index', compact(
            'inventories',
            'events'
        ));
    }


    public function updateQuantity(Request $request, Inventory $inventory)
    {
        $request->validate([
            'total_quantity' => 'required|integer|min:0|gte:' . $inventory->sold_quantity,
        ]);

        $inventory->update([
            'total_quantity' => $request->total_quantity,
        ]);

        return response()->json([
            'message' => 'Total quantity updated successfully!',
            'inventory' => $inventory,
        ]);
    }
}
