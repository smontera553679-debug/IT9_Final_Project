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
        'status', 
        'description', 
        'image'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Package::class);
    }
}