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
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
            $table->json('title')->after('id');
            $table->json('description')->after('title');
            $table->json('overview')->nullable()->after('description');
            $table->json('pricing')->nullable()->after('overview');
            $table->json('documents')->nullable()->after('pricing');
            $table->json('timeline')->nullable()->after('documents');
            $table->string('phone')->nullable()->after('timeline');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('appointment_url')->nullable()->after('whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['overview', 'pricing', 'documents', 'timeline', 'phone', 'whatsapp', 'appointment_url']);
            $table->dropColumn(['title', 'description']);
            $table->string('title')->after('id');
            $table->text('description')->after('title');
        });
    }
};
