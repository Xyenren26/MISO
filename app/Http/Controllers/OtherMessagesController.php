<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class OtherMessagesController extends Controller
{
    public function index($id = null)
    {
        // Get the messenger color for the authenticated user
        $messenger_color = Auth::user()->messenger_color;

        // Return the Chatify view with the necessary data
        return view('Chatify::pages.app', [
            'id' => $id ?? 0, // Use the provided ID or default to 0
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
        ]);
    }
    public function getUnseenMessages()
    {
        $unseenCount = DB::table('ch_messages')
            ->where('to_id', Auth::user()->id)
            ->where('seen', 0)
            ->count();

        return response()->json(['unseenCount' => $unseenCount]);
    }

}
