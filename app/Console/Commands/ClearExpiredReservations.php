<?php

namespace App\Console\Commands;

use App\Models\InventoryReservation;
use Illuminate\Console\Command;

class ClearExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Expired Inventory Reservations...';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        InventoryReservation::where('expires_at', '<', now())
            ->delete();
    }
}
