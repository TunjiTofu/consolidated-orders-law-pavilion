<?php

namespace App\Console\Commands;

use App\Models\ConsolidatedOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateConsolidatedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-consolidated-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re populates consolidated orders data every sunday by midnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            DB::statement("TRUNCATE TABLE consolidated_orders");

            DB::table("order_items")
                ->select([
                    'order_items.id',
                    'orders.id as order_id',
                    'customers.id as customer_id',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.sku',
                    'order_items.quantity',
                    'order_items.price as item_price',
                    DB::raw('order_items.quantity * order_items.price as line_total'),
                    'orders.order_date',
                    'orders.status as order_status',
                    'orders.total_amount as order_total',
                    'orders.created_at',
                    'orders.updated_at'
                ])
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->chunkById(1000, function ($items) {
                    ConsolidatedOrder::insert($items->toArray());
                });
        });

        $this->info('Consolidated orders refreshed successfully');
    }
}
