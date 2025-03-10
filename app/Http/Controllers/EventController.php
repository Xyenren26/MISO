<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Call the private cleanup function to delete expired events
        $this->cleanupExpiredEvents();

        // Fetch and return the events
        $events = Event::where('employee_id', auth()->id())->get();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        // Check if 'end' is set in the request
        if (!isset($request->end)) {
            return response()->json(['success' => false, 'message' => 'End date is required'], 400);
        }

        $event = Event::create([
            'employee_id' => auth()->id(),
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'all_day' => $request->allDay
        ]);

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'title' => $request->title
        ]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['success' => true]);
    }

    // Private cleanup function
    private function cleanupExpiredEvents()
    {
        $now = now(); // Get the current date and time

        // Find all events that have ended
        $expiredEvents = Event::where('end', '<', $now)->get();

        // Delete each expired event
        foreach ($expiredEvents as $event) {
            $event->delete();
        }
    }
}