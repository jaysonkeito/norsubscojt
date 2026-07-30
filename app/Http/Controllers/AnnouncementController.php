<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('poster')->latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'posted_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Announcement posted.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement removed.');
    }
}
