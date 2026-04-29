<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // 1. Fix the 'Data truncated' error by allowing longer status strings
            $table->string('status', 50)->change();
            
            // 2. Add 'customer_name' if it's missing to fix the "Column not found" error
            if (!Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name')->after('user_id')->nullable();
            }

            // 3. Just in case: ensure cancellation columns exist
            if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'cancellation_custom_reason')) {
                $table->text('cancellation_custom_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status', 255)->change();
            // We usually don't drop columns in down() if they might contain data, 
            // but you can add $table->dropColumn(...) here if you prefer a clean rollback.
        });
    }
};