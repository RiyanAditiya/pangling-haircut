<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Buat tabel bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barber_id')->constrained('users')->onDelete('cascade'); // ID Barber
            $table->foreignId('barbershop_id')->constrained()->onDelete('cascade');

            $table->date('booking_date');
            $table->time('booking_time');

            // Status booking
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            
            $table->integer('total_price')->default(0);

            // Bukti transfer
            $table->string('proof_of_payment')->nullable();

            // Kolom untuk index unik
            $table->unsignedBigInteger('barber_id_active')->nullable();

            $table->timestamps();

            // Unik hanya jika barber sedang aktif (pending/confirmed)
            $table->unique(['barber_id_active', 'booking_date', 'booking_time'], 'unique_active_booking');
        });

        // 2) Trigger BEFORE INSERT
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER bookings_before_insert
            BEFORE INSERT ON bookings
            FOR EACH ROW
            BEGIN
                -- Isi barber_id_active jika status aktif
                IF NEW.status IN ('pending','confirmed') THEN
                    SET NEW.barber_id_active = NEW.barber_id;
                ELSE
                    SET NEW.barber_id_active = NULL;
                END IF;
            END;
        SQL);

        // 3) Trigger BEFORE UPDATE
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER bookings_before_update
            BEFORE UPDATE ON bookings
            FOR EACH ROW
            BEGIN
                -- Perbarui barber_id_active sesuai status
                IF NEW.status IN ('pending','confirmed') THEN
                    SET NEW.barber_id_active = NEW.barber_id;
                ELSE
                    SET NEW.barber_id_active = NULL;
                END IF;
            END;
        SQL);
    }

    public function down(): void
    {
        // Hapus trigger terlebih dahulu
        DB::unprepared('DROP TRIGGER IF EXISTS bookings_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS bookings_before_update');

        // Hapus tabel bookings
        Schema::dropIfExists('bookings');
    }
};
