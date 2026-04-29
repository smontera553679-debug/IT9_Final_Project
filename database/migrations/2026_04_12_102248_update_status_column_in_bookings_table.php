<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // This changes the status column to a standard string to allow longer names
            $table->string('status')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Optional: revert back to whatever it was before if needed
            // $table->enum('status', ['pending', 'confirmed', 'cancelled'])->change();
        });
    }
};