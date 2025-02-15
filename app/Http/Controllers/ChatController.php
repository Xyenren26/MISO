<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        // Fetch all users except the current logged-in user
        $users = User::where('employee_id', '!=', Auth::user()->employee_id)->get();

        return view('chat', ['users' => $users]);
    }
  
    public function sendMessage(Request $request)
    {
        if (Auth::check()) {
            DB::table('users')
                ->where('employee_id', Auth::user()->employee_id)
                ->update(['last_activity' => Carbon::now()]);
        }
    
        $request->validate([
            'message' => 'required|string|max:500',
            'receiver_id' => 'required|exists:users,employee_id',
        ]);

        Message::create([
            'sender_id' => Auth::user()->employee_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'Message sent successfully']);
    }

    public function fetchMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::user()->employee_id)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::user()->employee_id);
        })
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'timestamp' => $message->created_at ? $message->created_at->toIso8601String() : null,
            ];
        });

        return response()->json($messages);
    }

    public function getUnreadMessageCount()
    {
        $receiverId = Auth::user()->employee_id;

        $unreadCounts = DB::table('messages')
            ->select('sender_id', DB::raw('COUNT(*) as unread_count'))
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->get();

        return response()->json($unreadCounts);
    }

    public function markAsRead($receiverId)
    {
        $userId = Auth::user()->employee_id;

        DB::table('messages')
            ->where('sender_id', $receiverId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // Fetch Active Users Based on Session Activity
    public function getActiveUsers()
    {
        $activeUsers = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.employee_id')
            ->where('sessions.user_id', '!=', Auth::user()->employee_id)
            ->where('sessions.last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->select('users.first_name', 'users.last_name', 'users.employee_id', 'users.email')
            ->get();

        return response()->json($activeUsers);
    }
}
