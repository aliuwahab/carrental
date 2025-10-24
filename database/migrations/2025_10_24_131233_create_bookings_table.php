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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // e.g., BK-20240115-A3X9
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('rental_days');
            $table->decimal('daily_rate', 10, 2); // Rate at time of booking
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['draft', 'pending', 'confirmed', 'completed', 'cancelled'])->default('draft');
            $table->enum('payment_method', ['paypal', 'mobile_money'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->datetime('terms_accepted_at')->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->datetime('expires_at')->nullable(); // For pending bookings
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for efficient queries
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['vehicle_id', 'start_date', 'end_date']);
            $table->index('booking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
