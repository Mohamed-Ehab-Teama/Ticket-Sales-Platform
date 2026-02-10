<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Main booking page
     */
    public function index()
    {
        return view('customer.index');
    }

    /**
     * Return all active events
     */
    public function getEvents()
    {
        $events = Event::where('status', 'active')
            ->select('id', 'title')
            ->get();

        return response()->json($events);
    }

    /**
     * Return dates for selected event
     */
    public function getDates(Event $event)
    {
        $dates = $event->dates()
            ->whereDate('date', '>=', now())
            ->select('id', 'date')
            ->get();

        return response()->json($dates);
    }

    /**
     * Return time slots for selected date
     */
    public function getTimeSlots(EventDate $date)
    {
        $timeSlots = $date->timeSlots()
            ->select('id', 'start_time', 'end_time')
            ->get();

        return response()->json($timeSlots);
    }

    /**
     * Return ticket types + availability for selected timeslot
     */
    public function getTickets(TimeSlot $timeslot)
    {
        $event = $timeslot->date->event;

        $tickets = $event->ticketTypes()
            ->with(['inventories' => function ($q) use ($timeslot) {
                $q->where('time_slot_id', $timeslot->id);
            }])
            ->get()
            ->map(function ($ticket) {
                $inventory = $ticket->inventories->first();

                return [
                    'id'        => $ticket->id,
                    'name'      => $ticket->name,
                    'price'     => $ticket->price,
                    'available' => $inventory->available,
                    // 'available' => $inventory?->available ?? 0,
                    // 'available' => max(
                    //     0,
                    //     ($inventory->total_quantity ?? 0)
                    //         - ($inventory->sold_quantity ?? 0)
                    // ),
                    'min' => $ticket->min_per_order,
                    'max' => $ticket->max_per_order,
                ];
            });

        return response()->json($tickets);
    }
}
