<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'control_no' => 'required|exists:tickets,control_no',
            'technical_support_id' => 'required|exists:users,employee_id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if rating already exists
        $existingRating = Rating::where('control_no', $request->control_no)
            ->where('technical_support_id', $request->technical_support_id)
            ->first();

        if ($existingRating) {
            return response()->json(['success' => false, 'message' => 'Rating already submitted.'], 400);
        }

        Rating::create([
            'control_no' => $request->control_no,
            'technical_support_id' => $request->technical_support_id,
            'rating' => $request->rating,
        ]);

        return response()->json(['success' => true, 'message' => 'Rating submitted successfully.']);
    }
}

