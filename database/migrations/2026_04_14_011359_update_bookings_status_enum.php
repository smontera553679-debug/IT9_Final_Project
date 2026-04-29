<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This adds 'rejected' to the allowed list for the bookings table status column
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'awaiting_cancellation', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This removes it if you ever need to rollback
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'awaiting_cancellation') NOT NULL DEFAULT 'pending'");
    }
};