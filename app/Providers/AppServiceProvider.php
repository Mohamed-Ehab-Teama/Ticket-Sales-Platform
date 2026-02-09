<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Policies\EventPolicy;
use App\Policies\OrderPolicy;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // =============== Roles Gate
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('staff', function ($user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('customer', function ($user) {
            return $user->role === 'customer';
        });
        // ===============

        // =============== Register Polices
        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
        // ===============
    }
}
