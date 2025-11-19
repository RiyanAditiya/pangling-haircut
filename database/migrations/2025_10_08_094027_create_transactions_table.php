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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Relasi ke Booking (nullable untuk walk-in)
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null'); 
            
            // Relasi ke User/Barber/Barbershop
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('customer_name_manual')->nullable();
            $table->foreignId('barber_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barbershop_id')->constrained()->onDelete('cascade');
            
            // Data Transaksi
            $table->string('service_name'); // Sederhana, bisa dikembangkan
            $table->enum('type', ['booking', 'walkin']);
            $table->integer('amount');
            $table->timestamp('transaction_date');
            $table->timestamps();
            $table->unique('booking_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
