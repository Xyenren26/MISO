<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // Display all announcements
    public function index()
    {
        $search = request('search');
        $announcements = Announcement::when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%")
                         ->orWhere('content', 'like', "%$search%");
        })->paginate(10);

        return view('announcement', compact('announcements'));
    }

    // Show the form for creating a new announcement
    public function create()
    {
        return view('announcements.create');
    }

    // Store a new announcement
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create($request->all());

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    // Show the form for editing an announcement
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    // Update an announcement
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement->update($request->all());

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    // Delete an announcement
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}