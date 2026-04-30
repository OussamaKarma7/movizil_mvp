<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $contract = \App\Models\Contract::first();
    if (!$contract) {
        echo "No contracts found.\n";
        exit;
    }
    echo "Contract #{$contract->id} found.\n";
    // Simulate view logic
    echo "Client: " . ($contract->client ? $contract->client->last_name : 'No client') . "\n";
    echo "Renewals count: " . $contract->renewals->count() . "\n";
    foreach($contract->renewals as $renewal) {
        echo "Renewal #{$renewal->id} start date: " . ($renewal->start_date ? $renewal->start_date->format('d/m/Y') : 'N/A') . "\n";
    }
    echo "Success!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
