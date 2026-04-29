<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Destinations Table
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->string('category');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 2. Tour Packages Table
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->decimal('price_per_person', 10, 2);
            $table->integer('duration_days');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description');
            $table->json('inclusions');
            $table->json('exclusions');
            $table->json('itinerary');
            $table->integer('max_group_size');
            $table->string('transport');
            $table->string('language');
            $table->string('currency');
            $table->integer('rating')->default(5);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 3. Bookings Table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->date('travel_date'); // Ensure this is present to avoid integrity errors
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // 4. Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('method'); 
            $table->string('proof_file')->nullable(); // Column fixed
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('destinations');
    }
};