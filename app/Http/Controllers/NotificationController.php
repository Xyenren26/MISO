<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification; // Import your Notification model

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        // Fetch unread notifications using `employee_id`
        $notifications = Notification::where('notifiable_id', Auth::user()->employee_id) 
            ->whereNull('read_at') // Only unread notifications
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        // Fetch and mark notification as read using `employee_id`
        $notification = Notification::where('notifiable_id', Auth::user()->employee_id)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        if ($user) {
            $user->unreadNotifications->markAsRead(); // Mark all notifications as read
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
    }
    
}
