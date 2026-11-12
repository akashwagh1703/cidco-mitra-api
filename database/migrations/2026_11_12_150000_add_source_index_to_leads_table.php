<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function hasIndex(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();
        $row = DB::selectOne(
            'SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $index]
        );

        return (int) ($row->c ?? 0) > 0;
    }

    public function up(): void
    {
        $addSource = !$this->hasIndex('leads', 'leads_source_index');
        $addCfc = !$this->hasIndex('leads', 'leads_cfc_file_no_index');

        if (!$addSource && !$addCfc) {
            return;
        }

        Schema::table('leads', function (Blueprint $table) use ($addSource, $addCfc) {
            if ($addSource) {
                $table->index('source');
            }
            if ($addCfc) {
                $table->index('cfc_file_no');
            }
        });
    }

    public function down(): void
    {
        $dropSource = $this->hasIndex('leads', 'leads_source_index');
        $dropCfc = $this->hasIndex('leads', 'leads_cfc_file_no_index');

        if (!$dropSource && !$dropCfc) {
            return;
        }

        Schema::table('leads', function (Blueprint $table) use ($dropSource, $dropCfc) {
            if ($dropSource) {
                $table->dropIndex(['source']);
            }
            if ($dropCfc) {
                $table->dropIndex(['cfc_file_no']);
            }
        });
    }
};
