<?php

namespace App\Console\Commands;

use App\Models\ConsolidatedOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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
     * @throws Throwable
     */
    public function handle()
    {
        $maxAttempts = 3;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $connection = DB::connection();

            try {
                $this->info("Attempt $attempt of $maxAttempts");

                // TRUNCATE causes implicit commit, so we do it outside transaction
                Log::alert('Truncating Consolidated Orders Table');
                ConsolidatedOrder::truncate();

                // Start explicit transaction
                $connection->beginTransaction();

                $processed = 0;
                $bar = $this->output->createProgressBar();
                Log::alert('Processing Consolidated Orders');
                $query = DB::table('order_items')
                    ->select([
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
                    ->orderBy('order_items.id');

                $query->chunk(1000, function ($items) use (&$processed, $bar) {
                    $records = $items->map(fn($item) => (array)$item)->toArray();
                    ConsolidatedOrder::insert($records);
                    $processed += count($records);
                    $bar->advance(count($records));
                });
                $bar->finish();
                $this->newLine();

                // Verify we still have transaction before committing
                if ($connection->transactionLevel() > 0) {
                    $connection->commit();
                    $this->info("Successfully processed $processed records");
                    Log::info("Successfully processed $processed records");
                    return 0;
                }

            } catch (Throwable $e) {
                Log::alert("Rolling back transaction failed: {$e->getMessage()}");
                if ($connection->transactionLevel() > 0) {
                    try {
                        $connection->rollBack();
                    } catch (Throwable $rollbackError) {
                        Log::error('Rollback failed: ' . $rollbackError->getMessage());
                    }
                }
                // Retry Logic
                Log::error("Attempt $attempt failed: " . $e->getMessage());
                sleep(5); // Wait before retry
            }
        }

        $this->error("Failed after $maxAttempts attempts");
        return 1;
    }
}
