<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ChatController extends Controller
{
    //new index for ticketing detection and user displaying on accordion 
    public function index()
    {
        $user = Auth::user();
        
        // Fetch all technical support users, excluding the currently authenticated user
        $technicalSupportUsers = User::where('account_type', 'technical_support')
            ->where('employee_id', '!=', $user->employee_id) // Exclude the logged-in user
            ->get();
        
        // Fetch assigned employees (end users) for technical support users
        $assignedEndUsers = collect();  // Default empty collection
        if ($user->account_type === 'technical_support') {
            $assignedEndUserIds = Ticket::where('technical_support_id', $user->employee_id)
                ->pluck('employee_id')
                ->unique()
                ->filter();
    
            $assignedEndUsers = User::whereIn('employee_id', $assignedEndUserIds)
                ->where('account_type', 'end_user')
                ->get();
        }
        
        // Fetch assigned technical support for end users (exclude those with "completed" tickets)
        $assignedTechUsers = collect();  // Default empty collection
        if ($user->account_type === 'end_user') {
            // Get the technical support users assigned to this end user, but exclude those whose ticket status is 'completed'
            $assignedTechIds = Ticket::where('employee_id', $user->employee_id)
                ->whereNotNull('technical_support_id')
                ->where('status', '!=', 'completed') // Exclude 'completed' tickets
                ->pluck('technical_support_id')
                ->unique()
                ->filter();
    
            $assignedTechUsers = User::whereIn('employee_id', $assignedTechIds)
                ->where('account_type', 'technical_support')
                ->get();
        }
        
        return view('chat', compact('technicalSupportUsers', 'assignedEndUsers', 'assignedTechUsers'));
    }


//for restrictio ng chat sa technical support pag tapos na yung ticket between employee
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

    $senderId = Auth::user()->employee_id;
    $receiverId = $request->receiver_id;

    // Check if there is an existing ticket between sender and receiver
    $ticket = Ticket::where(function ($query) use ($senderId, $receiverId) {
            $query->where('employee_id', $senderId)
                  ->where('technical_support_id', $receiverId);
        })
        ->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('employee_id', $receiverId)
                  ->where('technical_support_id', $senderId);
        })
        ->latest()
        ->first();

    // Restrict message sending if the ticket is completed
    if ($ticket && $ticket->status === 'completed') {
        return response()->json(['error' => 'You cannot message here, this conversation has expired.'], 403);
    }

    // Proceed with message sending
    Message::create([
        'sender_id' => $senderId,
        'receiver_id' => $receiverId,
        'message' => $request->message,
    ]);

    return response()->json(['status' => 'Message sent successfully']);
}


    //ginawa ni rogelio hanngang line 145
    public function fetchMessages($receiverId)
    {
        $userId = Auth::user()->employee_id;
        $userType = Auth::user()->account_type; // Get current user's account type
    
        // Fetch ticket between these users (if it exists)
        $ticket = Ticket::where(function ($query) use ($userId, $receiverId) {
            $query->where('employee_id', $userId)
                  ->where('technical_support_id', $receiverId);
        })
        ->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('employee_id', $receiverId)
                  ->where('technical_support_id', $userId);
        })
        ->latest()
        ->first();
    
        // Fetch messages
        $messages = Message::where(function ($query) use ($receiverId, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($receiverId, $userId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'timestamp' => optional($message->created_at)->toIso8601String(),
            ];
        });
    
        // Convert messages to an array for manual manipulation
        $messages = $messages->toArray();
    
        // If a ticket exists and is still active, insert connection message
        if ($ticket && $ticket->status !== 'completed') {
            $connectionMessage = [
                'id' => null,
                'message' => ($userType === 'end_user') 
                    ? "You are now connected to our technical support for this ticket." 
                    : "You are now connected to the Employee.",
                'sender_id' => 'system',
                'receiver_id' => $userId,
                'timestamp' => optional($ticket->created_at)->toIso8601String(),
            ];
    
            array_push($messages, $connectionMessage);
        }
    
        // If ticket is completed, append system message
        if ($ticket && $ticket->status === 'completed') {
            $completionTimestamp = optional($ticket->updated_at)->toIso8601String();
    
            $messages[] = [
                'id' => null,
                'message' => ($userType === 'end_user') 
                    ? "Thanks for trusting our technical team. Your ticket is resolved." 
                    : "Your assistance is appreciated. The ticket is now resolved.",
                'sender_id' => 'system',
                'receiver_id' => $userId,
                'timestamp' => $completionTimestamp,
            ];
        }
    
        // Sort messages by timestamp to maintain correct order
        usort($messages, function ($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });
    
        return response()->json($messages);
    }
    
    
 //UNREAD MESSAGES FUNCTION   
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


    // Get active users
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

    public function passTicket(Request $request)
{
    $request->validate([
        'ticket_control_no' => 'required|exists:tickets,control_no',
        'new_technical_support' => 'required|exists:users,employee_id',
    ]);

    $ticket = Ticket::where('control_no', $request->ticket_control_no)->first();

    if (!$ticket) {
        return response()->json(['error' => 'Ticket not found'], 404);
    }

    $previousTechnicalSupportId = $ticket->technical_support_id;
    $newTechnicalSupportId = $request->new_technical_support;

    // Update the ticket's assigned technical support
    $ticket->update([
        'technical_support_id' => $newTechnicalSupportId
    ]);

    // Fire a real-time event to notify about reassignment
    broadcast(new \App\Events\ChatReassignmentEvent($ticket->employee_id, $newTechnicalSupportId));

    return response()->json([
        'message' => 'Ticket successfully reassigned!',
        'previous_technical_support_id' => $previousTechnicalSupportId,
        'new_technical_support_id' => $newTechnicalSupportId,
    ]);
}

}
