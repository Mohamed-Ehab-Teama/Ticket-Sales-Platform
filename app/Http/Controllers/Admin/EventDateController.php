<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDate;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventDateController extends Controller
{

    public function index(Event $event)
    {
        $dates = $event->dates()->latest()->paginate(12);
        return view('admin.event-dates.index', compact('event', 'dates'));
    }




    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        DB::transaction(function () use ($data, $event) {
            $date = $event->dates()->create($data);
            InventoryService::createForEventDate($date);
        });

        return response()->json([
            'message'   => 'EventDate created successfully!',
            'data'      => $event,
        ], 201);
    }



    public function update(Request $request, Event $event, EventDate $date)
    {
        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date->update($data);

        return response()->json([
            'message'   => 'EventDate Updated successfully!',
            'data'      => $event,
        ], 201);
    }


    public function edit(Event $event, EventDate $date)
    {
        return response()->json($date);
    }


    public function destroy(Event $event, EventDate $date)
    {
        $date->delete();
        return response()->json([
            'message'   => 'EventDate Deleted successfully!',
            'data'      => [],
        ], 201);
    }
}
