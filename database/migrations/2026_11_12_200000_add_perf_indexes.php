<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->index('next_action_date');
            $table->index(['case_status', 'date_opened']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['service_id', 'appointment_date', 'appointment_time', 'status'], 'appointments_slot_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['next_action_date']);
            $table->dropIndex(['case_status', 'date_opened']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_slot_lookup_idx');
        });
    }
};
