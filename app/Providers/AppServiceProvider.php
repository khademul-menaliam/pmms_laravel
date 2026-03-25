<?php

namespace App\Providers;

use App\Support\ReminderCenter;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Authenticate::redirectUsing(fn () => route('login'));

        View::composer('layouts.app', function ($view): void {
            $pendingAlertsCount = 0;

            try {
                if (
                    auth()->check()
                    && Schema::hasTable('incomes')
                    && Schema::hasTable('expenses')
                    && Schema::hasTable('given_loans')
                    && Schema::hasTable('taken_loans')
                    && Schema::hasTable('reminders')
                ) {
                    $pendingAlertsCount = ReminderCenter::countUpcoming(auth()->user());
                }
            } catch (Throwable) {
                $pendingAlertsCount = 0;
            }

            $view->with('pendingAlertsCount', $pendingAlertsCount);
        });
    }
}
