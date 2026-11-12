<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ados', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('contact', 30)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Seed from existing lead ADO values (unique by name)
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'ado_name')) {
            $rows = DB::table('leads')
                ->select('ado_name', DB::raw('MAX(ado_contact) as contact'))
                ->whereNotNull('ado_name')
                ->where('ado_name', '!=', '')
                ->groupBy('ado_name')
                ->get();

            $now = now();
            foreach ($rows as $row) {
                DB::table('ados')->insertOrIgnore([
                    'name' => trim($row->ado_name),
                    'contact' => $row->contact ? trim($row->contact) : null,
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ados');
    }
};
