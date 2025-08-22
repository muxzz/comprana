<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete orders in null';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fechaHaceUnMes = Carbon::now()->subMonth();

        Order::whereNull('payment_id')
            ->where('created_at', '<', $fechaHaceUnMes)
            ->delete();
    }
}
