<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->date('date_opened')->nullable()->after('source');
            $table->string('client_location')->nullable()->after('date_opened');
            $table->string('cidco_node')->nullable()->after('client_location');
            $table->string('ado_name')->nullable()->after('cidco_node');
            $table->string('ado_contact', 30)->nullable()->after('ado_name');
            $table->string('cfc_file_no')->nullable()->after('ado_contact');
            $table->string('service_type')->nullable()->after('cfc_file_no');
            $table->string('case_status')->nullable()->after('service_type');
            $table->decimal('gross_fee', 12, 2)->nullable()->after('case_status');
            $table->decimal('paper_office_exp', 12, 2)->nullable()->after('gross_fee');
            $table->decimal('cidco_fees', 12, 2)->nullable()->after('paper_office_exp');
            $table->date('next_action_date')->nullable()->after('cidco_fees');
            $table->string('next_action_item')->nullable()->after('next_action_date');

            $table->index('case_status');
            $table->index('cidco_node');
            $table->index('service_type');
            $table->index('date_opened');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['case_status']);
            $table->dropIndex(['cidco_node']);
            $table->dropIndex(['service_type']);
            $table->dropIndex(['date_opened']);
            $table->dropColumn([
                'date_opened',
                'client_location',
                'cidco_node',
                'ado_name',
                'ado_contact',
                'cfc_file_no',
                'service_type',
                'case_status',
                'gross_fee',
                'paper_office_exp',
                'cidco_fees',
                'next_action_date',
                'next_action_item',
            ]);
        });
    }
};
