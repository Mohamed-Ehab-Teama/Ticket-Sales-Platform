<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\TimeSlot;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeSlotController extends Controller
{
    public function index(Event $event, EventDate $date)
    {
        // Ensure date belongs to event
        abort_unless($date->event_id === $event->id, 404);

        $timeSlots = $date->timeSlots()->latest()->get();

        return view('admin.time-slots.index', compact(
            'event',
            'date',
            'timeSlots'
        ));
    }

    public function store(Request $request, Event $event, EventDate $date)
    {
        abort_unless($date->event_id === $event->id, 404);

        $data = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'capacity'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data, $date) {
            $timeslot = $date->timeSlots()->create($data);
            InventoryService::createForTimeSlot($timeslot);
        });

        return response()->json([
            'message'   => 'TimeSlot added successfully!',
            'data'      => [
                $event,
                $date
            ],
        ], 201);
    }

    public function edit(Event $event, EventDate $date, TimeSlot $timeSlot)
    {
        abort_unless($date->event_id === $event->id, 404);
        abort_unless($timeSlot->event_date_id === $date->id, 404);

        return response()->json($timeSlot);
    }

    public function update(Request $request, Event $event, EventDate $date, TimeSlot $timeSlot)
    {
        abort_unless($date->event_id === $event->id, 404);
        abort_unless($timeSlot->event_date_id === $date->id, 404);

        $data = $request->validate([
            'start_time' => 'required',
            'end_time'   => 'required',
            'capacity'   => 'required|integer|min:1',
        ]);

        $timeSlot->update($data);

        return response()->json([
            'message'   => 'Time slot updated successfully!',
            'data'      => [
                $event,
                $date
            ],
        ], 201);
    }

    public function destroy(Event $event, EventDate $date, TimeSlot $timeSlot)
    {
        abort_unless($date->event_id === $event->id, 404);
        abort_unless($timeSlot->event_date_id === $date->id, 404);

        $timeSlot->delete();

        return response()->json([
            'message'   => 'Time slot deleted successfully!',
            'data'      => [$event, $date],
        ], 201);
    }
}
