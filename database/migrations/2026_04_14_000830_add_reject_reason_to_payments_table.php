<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    // We use a raw statement because changing ENUMs via Blueprint can be tricky
    DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'awaiting_cancellation', 'rejected') NOT NULL DEFAULT 'pending'");
}

public function down(): void
{
    DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'awaiting_cancellation') NOT NULL DEFAULT 'pending'");
}
};
