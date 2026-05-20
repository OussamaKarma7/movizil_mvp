<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$columns = DB::select('SHOW INDEX FROM contracts');
echo "INDEXES ON contracts:\n";
foreach ($columns as $column) {
    echo "- " . $column->Key_name . "\n";
}
echo "\n";
