<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot messages using local knowledge base.
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $text = Str::lower($request->input('message'));
        $response = $this->getExpertResponse($text);

        // Simulate a small delay for "thinking"
        usleep(500000); 

        return response()->json(['response' => $response]);
    }

    /**
     * Local Knowledge Base Logic
     */
    private function getExpertResponse($text)
    {
        // 1. GREETINGS
        if (Str::contains($text, ['bonjour', 'salut', 'hello'])) {
            return "Bonjour ! Je suis l'expert technique d'Universal Invest Strategy. Comment puis-je vous aider avec la gestion de vos contrats ou de votre comptabilité aujourd'hui ?";
        }

        // 2. CONTRACTS LOGIC
        if (Str::contains($text, ['contrat', 'calcul', 'droit', 'consomme', 'surplus'])) {
            if (Str::contains($text, ['surplus'])) {
                return "Le surplus est calculé ainsi : [Réel/J] - [Droit Consommé]. Cela représente les jours d'occupation dépassant la durée initiale prévue au contrat.";
            }
            if (Str::contains($text, ['droit'])) {
                return "Le 'Droit Consommé' est la durée totale prévue du contrat (Date Fin - Date Début).";
            }
            return "Dans la gestion des contrats, nous suivons : 1. Droit Consommé (durée prévue), 2. Réel/J (jours écoulés après échéance), 3. Valeur HT (calclulée à 165 DH/mois si non saisie).";
        }

        // 3. ACCOUNTING / JOURNAL
        if (Str::contains($text, ['comptabilité', 'journal', 'compte', '3421', '7121', 'vendes'])) {
            return "Pour la comptabilité, nous utilisons principalement deux comptes : le compte 3421 (Clients) et le compte 7121 (Ventes de services). Vous pouvez consulter l'équilibre Débit/Crédit dans le Journal des Ventes.";
        }

        // 4. SAGE EXPORT
        if (Str::contains($text, ['sage', 'export', 'import', 'synchronisation'])) {
            return "L'export Sage génère automatiquement des fichiers TXT formatés pour Sage 100 dans le dossier 'C:\Sage_Import'. La synchronisation peut être déclenchée manuellement depuis la page Export.";
        }

        // 5. INVOICES
        if (Str::contains($text, ['facture', 'pdf', 'email', 'payer', 'payée'])) {
            return "Vous pouvez générer des factures PDF, les envoyer par email aux clients et suivre leur statut (En attente / Payée) directement depuis le module Facturation.";
        }

        // 6. CLIENTS
        if (Str::contains($text, ['client', 'société', 'ice', 'entreprise'])) {
            return "Le système permet de gérer les fiches clients avec leurs informations légales (ICE, Registre du Commerce) et de lier chaque client à un ou plusieurs contrats de domiciliation.";
        }

        // DEFAULT RESPONSE (RESTRICTED TO PROJECT)
        return "Désolé, je suis programmé pour répondre uniquement aux questions concernant la plateforme Universal Invest Strategy (Contrats, Factures, Comptabilité, Sage). Pouvez-vous préciser votre question sur l'un de ces sujets ?";
    }
}
