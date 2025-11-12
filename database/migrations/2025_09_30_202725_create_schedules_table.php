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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('users')->onDelete('cascade');           // barber
            $table->foreignId('barbershop_id')->nullable()->constrained('barbershops')->onDelete('cascade');
            $table->enum('day_of_week', [
                'monday','tuesday','wednesday','thursday','friday','saturday','sunday'
            ]);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('slot_duration')->default(60); // default 60 menit
            $table->boolean('is_day_off')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_user_id_foreign');
            $table->dropForeign('schedules_barbershop_id_foreign');
        });
        Schema::dropIfExists('schedules');
    }
};
