<?php

namespace Database\Seeders;

use App\Models\ConsolidatedOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsolidatedOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE consolidated_orders');

        DB::table('order_items')
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
            ->orderBy('order_items.id')
            ->chunk(1000, function ($items) {
                $records = $items->map(function ($item) {
                    return [
                        'order_id' => $item->order_id,
                        'customer_id' => $item->customer_id,
                        'customer_name' => $item->customer_name,
                        'customer_email' => $item->customer_email,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'sku' => $item->sku,
                        'quantity' => $item->quantity,
                        'item_price' => $item->item_price,
                        'line_total' => $item->line_total,
                        'order_date' => $item->order_date,
                        'order_status' => $item->order_status,
                        'order_total' => $item->order_total,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at
                    ];
                })->toArray();

                DB::table('consolidated_orders')->insert($records);
            });
    }
}
