<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'country',
        'title', 
        'category', 
        'status', 
        'description', 
        'image'
    ];

    /**
     * Get all the packages for the destination.
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get all the bookings for the destination through its packages.
     * This allows Destination::withCount('bookings') to work.
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Package::class);
    }
}