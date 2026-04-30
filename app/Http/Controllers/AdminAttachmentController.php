<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;

class AdminAttachmentController extends Controller
{
    public function clientRegistrationAttachment($id, $type)
    {
        $client = Client::findOrFail($id);

        $path = match ($type) {
            'cin' => $client->registration_cin_path,
            'company_doc' => $client->registration_company_doc_path,
            default => null,
        };

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download($path);
    }

    public function contractAttachment($id, $type)
    {
        $contract = Contract::findOrFail($id);

        $path = match ($type) {
            'cin' => $contract->cin_path,
            'certificat' => $contract->certificat_path,
            default => null,
        };

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download($path);
    }
}
