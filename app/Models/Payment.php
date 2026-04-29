<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Fixes: MassAssignmentException on [booking_id]
    protected $fillable = [
        'booking_id',
        'method',
        'proof_file',
        'amount',
        'status',
        'reject_reason'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}