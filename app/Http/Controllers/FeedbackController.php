<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        // One feedback per booking per user
        Feedback::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'booking_id' => $request->booking_id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return response()->json(['success' => true]);
    }
}