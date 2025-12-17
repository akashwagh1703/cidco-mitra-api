<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Delete services with malformed JSON (escaped unicode)
$deleted = DB::table('services')
    ->where('title', 'like', '%\\\\u%')
    ->delete();

echo "Deleted {$deleted} malformed services\n";

// Count remaining services
$count = DB::table('services')->count();
echo "Remaining services: {$count}\n";
