<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Ticket;

class RatingController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'control_no' => 'required|exists:tickets,control_no',
            'technical_support_id' => 'required|exists:users,employee_id',
            'rating' => 'required|integer|min:1|max:5',
            'remark' => 'nullable|string|max:500', // Validate remark field
        ]);
    
        // Check if rating already exists
        $existingRating = Rating::where('control_no', $request->control_no)
            ->where('technical_support_id', $request->technical_support_id)
            ->first();
    
        if ($existingRating) {
            return response()->json(['success' => false, 'message' => 'Rating already submitted.'], 400);
        }
    
        // Create the rating
        Rating::create([
            'control_no' => $request->control_no,
            'technical_support_id' => $request->technical_support_id,
            'rating' => $request->rating,
            'remark' => $request->remark, // Save remark
        ]);
    
        // Update the time_out column in the tickets table
        $ticket = Ticket::where('control_no', $request->control_no)->first();
        if ($ticket) {
            $ticket->time_out = now(); // Set time_out to the current timestamp
            $ticket->save();
        }
    
        return response()->json(['success' => true, 'message' => 'Rating submitted successfully.']);
    }
}