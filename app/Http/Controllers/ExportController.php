<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\AccountingEntry;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        return view('pages.export.index');
    }

    /**
     * Export Clients to CSV (Excel compatible)
     */
    public function exportClients()
    {
        $clients = Client::with('company')->get();
        $fileName = 'clients_export_' . date('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($clients) {
            $handle = fopen('php://output', 'w');
            // Adding BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($handle, ['ID', 'Nom', 'Prénom', 'CIN', 'Téléphone', 'Email', 'Adresse', 'Entreprise', 'ICE', 'RC', 'IF'], ';');

            foreach ($clients as $client) {
                fputcsv($handle, [
                    $client->id,
                    $client->last_name,
                    $client->first_name,
                    $client->cin,
                    $client->phone,
                    $client->email,
                    $client->address,
                    $client->company->company_name ?? '-',
                    $client->company->ice ?? '-',
                    $client->company->rc ?? '-',
                    $client->company->if ?? '-',
                ], ';');
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * Export Contracts to CSV (Excel compatible)
     */
    public function exportContracts()
    {
        $contracts = Contract::with('client.company')->get();
        $fileName = 'contracts_export_' . date('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($contracts) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($handle, ['ID', 'Client', 'Entreprise', 'Type', 'Date Début', 'Date Fin', 'Durée (Mois)', 'Prix (MAD)', 'Status'], ';');

            foreach ($contracts as $contract) {
                fputcsv($handle, [
                    $contract->id,
                    $contract->client->first_name . ' ' . $contract->client->last_name,
                    $contract->client->company->company_name ?? '-',
                    $contract->type,
                    $contract->start_date ? $contract->start_date->format('d/m/Y') : '-',
                    $contract->end_date ? $contract->end_date->format('d/m/Y') : '-',
                    $contract->duration,
                    $contract->price,
                    $contract->status,
                ], ';');
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * Export Accounting Entries to Sage-compatible Semicolon Format
     */
    public function exportInvoicesTxt()
    {
        $entries = AccountingEntry::with('invoice.contract.client.company')->orderBy('date', 'asc')->get();
        $fileName = 'IMPORT_SAGE_' . date('Ymd') . '.txt';

        $response = new StreamedResponse(function () use ($entries) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $handle = fopen('php://output', 'w');
            
            foreach ($entries as $entry) {
                // Exact 9-column format matching friend's working sample
                $journal = 'VTE';
                $dateString = $entry->date ? $entry->date->format('dmy') : date('dmy');
                
                $piece = mb_substr(trim($entry->invoice->invoice_number ?? 'FAC'), 0, 13);
                
                $acc = str_pad($entry->account_number, 8, '0', STR_PAD_RIGHT);
                $tier = mb_substr(trim($entry->third_party_account ?? ''), 0, 17);
                
                // Clean label, replace semicolons with space to avoid breaking columns
                $label = mb_substr(str_replace([';', "\t", "\r", "\n", '"', "'"], ' ', trim($entry->label)), 0, 35);
                
                // Date d'échéance only for client account lines (Moroccan standard 3421)
                $dueDate = (str_starts_with($acc, '3421')) 
                    ? ($entry->invoice->date ? $entry->invoice->date->format('dmy') : date('dmy')) 
                    : '';
                
                $debit = number_format((float)$entry->debit, 2, ',', '');
                $credit = number_format((float)$entry->credit, 2, ',', '');

                // Final 9-column string: Journal;Date;Piece;CompteG;CompteT;Libelle;Echeance;Debit;Credit
                $line = "{$journal};{$dateString};{$piece};{$acc};{$tier};{$label};{$dueDate};{$debit};{$credit}\r\n";
                fwrite($handle, mb_convert_encoding($line, 'Windows-1252', 'UTF-8'));
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/plain; charset=windows-1252');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * Export Journal to CSV (Excel compatible)
    */
    public function exportJournalExcel()
    {
        $entries = AccountingEntry::with('invoice.contract.client.company')->orderBy('date', 'asc')->get();
        $fileName = 'EXPORT_EXCEL_' . date('Ymd') . '.csv';

        $response = new StreamedResponse(function () use ($entries) {
            $handle = fopen('php://output', 'w');
            
            // Mirroring the Sage 9-column format for Excel reconciliation
            // Header for clarity in Excel
            fputcsv($handle, [
                'Journal', 'Date', 'Piece', 'CompteG', 'CompteT', 'Libelle', 'Echeance', 'Debit', 'Credit'
            ], ';');

            foreach ($entries as $entry) {
                $journal = 'VTE';
                $dateString = $entry->date ? $entry->date->format('d/m/Y') : date('d/m/Y');
                $piece = $entry->invoice->invoice_number ?? 'FAC';
                $acc = str_pad($entry->account_number, 8, '0', STR_PAD_RIGHT);
                $tier = $entry->third_party_account ?? '';
                $label = $entry->label;
                
                $dueDate = ($entry->account_number == '34210000') 
                    ? ($entry->invoice->date ? $entry->invoice->date->format('d/m/Y') : date('d/m/Y')) : '';
                
                $debit = number_format((float)$entry->debit, 2, ',', '');
                $credit = number_format((float)$entry->credit, 2, ',', '');

                fputcsv($handle, [
                    $journal, $dateString, $piece, $acc, $tier, $label, $dueDate, $debit, $credit
                ], ';');
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

}
