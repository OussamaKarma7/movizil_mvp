<?php

namespace App\Services;

use App\Models\AccountingEntry;
use Illuminate\Support\Facades\Log;

class SageService
{
    /**
     * Synchronize all accounting entries to the local Sage import folder.
     */
    public function syncNow(): array
    {
        $importFolder = 'C:\\Sage_Import';
        $fileName = 'SYNC_SAGE_' . date('Ymd_His') . '.txt';
        $path = $importFolder . DIRECTORY_SEPARATOR . $fileName;

        try {
            if (!file_exists($importFolder)) {
                mkdir($importFolder, 0777, true);
            }

            // Cleanup old sync files to avoid clutter (Optional)
            $this->cleanupImportFolder($importFolder);

            $content = $this->generateSageContent();
            file_put_contents($path, $content);

            return ['success' => true, 'path' => $path];
        } catch (\Exception $e) {
            Log::error('Sage Sync Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate encoded Sage txt content (11 columns)
     */
    public function generateSageContent(): string
    {
        $entries = AccountingEntry::with('invoice.contract.client.company')->orderBy('date', 'asc')->get();
        $output = '';

        foreach ($entries as $entry) {
            $journal = 'VTE';
            $date = $entry->date->format('dmy'); // Date de pièce
            $piece = mb_substr(str_pad(trim($entry->invoice->invoice_number ?? 'FAC'), 10, '0', STR_PAD_LEFT), 0, 13);
            $facture = mb_substr(trim($entry->invoice->invoice_number ?? '-'), 0, 13);
            $reference = mb_substr(trim($entry->invoice->contract->client->company->company_name ?? $entry->invoice->contract->client->last_name), 0, 35);
            $accG = str_pad($entry->account_number, 8, '0', STR_PAD_RIGHT);
            $accT = mb_substr(trim($entry->third_party_account ?? ''), 0, 17);
            $label = mb_substr(str_replace([';', "\t", "\r", "\n", '"', "'"], ' ', trim($entry->label)), 0, 35);
            $dueDate = $entry->date->format('dmy');
            $debit = number_format((float)$entry->debit, 2, ',', '');
            $credit = number_format((float)$entry->credit, 2, ',', '');

            // New 11 column structure matching EXACTLY the Sage screenshot drop-down
            $line = "{$journal};{$date};{$piece};{$facture};{$reference};{$accG};{$accT};{$label};{$dueDate};{$debit};{$credit}\r\n";
            $output .= mb_convert_encoding($line, 'Windows-1252', 'UTF-8');
        }

        return $output;
    }

    /**
     * Keep only the latest few sync files
     */
    private function cleanupImportFolder($folder)
    {
        $files = glob($folder . DIRECTORY_SEPARATOR . 'SYNC_SAGE_*.txt');
        if (count($files) > 10) {
            // Sort by modify time and remove oldest
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            unlink($files[0]);
        }
    }
}
