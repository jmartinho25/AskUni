<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view to create a new notification
        return view('notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'read_status' => 'boolean',
            'users_id' => 'required|exists:users,id',
        ]);

        // Create the notification
        Notification::create([
            'content' => $request->content,
            'date' => $request->date,
            'read_status' => $request->read_status ?? false,
            'users_id' => $request->users_id,
        ]);

        // Redirect with a success message
        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        // Return a view to display the notification details
        return view('notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        // Return a view to edit the notification
        return view('notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'read_status' => 'boolean',
            'users_id' => 'required|exists:users,id',
        ]);

        // Update the notification
        $notification->update([
            'content' => $request->content,
            'date' => $request->date,
            'read_status' => $request->read_status ?? false,
            'users_id' => $request->users_id,
        ]);

        // Redirect with a success message
        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        // Delete the notification
        $notification->delete();

        // Redirect with a success message
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }

    /**
     * Mark a notification as read via API.
     */
    public function markAsReadAPI($id)
    {
        $user = Auth::user();
        $notification = Notification::find($id);

        if (!$notification || $notification->users_id !== $user->id) {
            return response()->json(['error' => 'Notification not found or unauthorized.'], 404);
        }

        $notification->read_status = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read.']);
    }
}
