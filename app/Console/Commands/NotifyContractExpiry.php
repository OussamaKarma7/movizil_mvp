<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyContractExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:notify-expiry';

    protected $description = 'Notifier les clients et les admins des contrats arrivant à échéance dans 7 ou 15 jours';

    public function handle()
    {
        $days = [7, 15];
        $now = now()->startOfDay();

        foreach ($days as $day) {
            $expiryDate = $now->copy()->addDays($day)->toDateString();
            
            $contracts = \App\Models\Contract::with(['client', 'client.user'])
                ->where('status', 'active')
                ->whereDate('end_date', $expiryDate)
                ->get();

            $this->info("Trouvé " . $contracts->count() . " contrats expirant dans $day jours.");

            foreach ($contracts as $contract) {
                /** @var \App\Models\Contract $contract */
                // 1. Notify Client
                if ($contract->client && $contract->client->email) {
                    \Illuminate\Support\Facades\Mail::to($contract->client->email)
                        ->send(new \App\Mail\ContractExpiryMail($contract, $day));
                }

                // 2. Notify Admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    \Illuminate\Support\Facades\Mail::to($admin->email)
                        ->send(new \App\Mail\ContractExpiryMail($contract, $day));
                }
                
                $this->info("Notifié pour le contrat #{$contract->id} (Client: {$contract->client->last_name})");
            }
        }

        $this->info('Toutes les notifications ont été envoyées.');
    }
}
