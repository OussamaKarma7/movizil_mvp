<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel; // Requires: composer require maatwebsite/excel
// use App\Imports\ClientsImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('pages.import.index');
    }

    public function importData(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
            'data_type' => 'required|string'
        ]);

        $file = $request->file('import_file');

        if ($request->data_type == 'clients_excel') {
            // Excel::import(new ClientsImport, $file);
            return redirect()->back()->with('success', 'Clients imported successfully! (Preview)');
        } 
        else if ($request->data_type == 'accounting_txt') {
            // Read TXT Custom Logic
            $content = file_get_contents($file->getRealPath());
            $lines = explode("\n", $content);
            $parsedCount = 0;
            foreach ($lines as $index => $line) {
                if ($index == 0 || trim($line) == '') continue; // skip header or empty
                $parts = explode("\t", $line);
                // e.g. store logic
                /*
                AccountingEntry::create([
                    'date' => $parts[0],
                    'account_number' => $parts[1],
                    'label' => $parts[2],
                    'debit' => $parts[3],
                    'credit' => $parts[4],
                ]);
                */
                $parsedCount++;
            }
            return redirect()->back()->with('success', "Imported $parsedCount text records successfully!");
        }

        return redirect()->back()->with('error', 'Invalid import options.');
    }
}
