<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 
        'package_id', 
        'customer_name', 
        'num_adults',   
        'num_children', 
        'travel_date', 
        'status',
        'cancellation_reason',       
        'cancellation_custom_reason',
        'feedback_rating',
        'feedback_comment',
        'cancellation_reason',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}