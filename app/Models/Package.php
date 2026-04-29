<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'destination_id',
        'price_per_person',
        'duration_days',
        'status',
        'description',
        'inclusions',
        'exclusions',
        'itinerary',
        'max_group_size',
        'transport',
        'language',
        'currency',
        'rating',
        'image',
        'images',
        'is_featured',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'inclusions'   => 'array',
        'exclusions'   => 'array',
        'itinerary'    => 'array',
        'transport'    => 'array',
        'images'       => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function destination()
    {
        return $this->belongsTo(Destination::class)->withTrashed();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function hasActiveBookings(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
}