<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('dates')->latest()->paginate(12);
        return view('admin.events.index', compact('events'));
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'status'        => 'required|in:draft,active,archived',
        ]);

        $event = Event::create($data);

        return response()->json([
            'message'   => 'Event created successfully!',
            'data'      => $event,
        ], 201);
    }



    public function edit(Event $event)
    {
        return response()->json($event);
    }



    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'status'        => 'required|in:draft,active,archived',
        ]);

        $event->update($data);

        return response()->json([
            'message'   => 'Event Updated successfully!',
            'data'      => $event,
        ], 200);
    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json([
            'message'   => 'Event Deleted successfully!',
            'data'      => [],
        ], 200);
    }
    // 
}
