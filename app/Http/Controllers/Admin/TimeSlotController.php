<?php

namespace App\Http\Controllers\Admin;

use App\Models\TimeSlot;
use App\Models\EventDate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeSlotController extends Controller
{

    public function index(EventDate $date)
    {
        $slots = $date->timeSlots()->latest()->get();
        return view('admin.time_slots.index', compact('date', 'slots'));
    }


    public function store(Request $request, EventDate $date)
    {
        $data = $request->validate([
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'capacity'      => 'required|integer|min:1',
        ]);

        $date->timeSlots()->create($data);

        return redirect()->route('admin.dates.time-slots.index', $date)->with('success', 'Time slot added successfully!');
    }


    public function edit(EventDate $eventDate)
    {
        return response()->json($eventDate);
    }


    public function update(Request $request, EventDate $date, TimeSlot $timeSlot)
    {
        $data = $request->validate([
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'capacity'      => 'required|integer|min:1',
        ]);

        $timeSlot->update($data);

        return redirect()->route('admin.dates.time-slots.index', $date)->with('success', 'Time slot updated successfully!');
    }



    public function destroy(EventDate $date, TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('admin.dates.time-slots.index', $date)->with('success', 'Time slot deleted successfully!');
    }
}
