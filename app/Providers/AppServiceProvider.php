<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.sidebar', function ($view) {
            $view->with('pendingContractsCount', \App\Models\Contract::where('status', 'pending')->count());
            
            $fifteenDays = now()->addDays(15)->toDateString();
            $today = now()->toDateString();
            
            $view->with('expiringContractsCount', \App\Models\Contract::where('status', 'active')
                ->where('end_date', '<=', $fifteenDays)
                ->where('end_date', '>=', $today)
                ->whereDoesntHave('renewals')
                ->count());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });
    }
}
