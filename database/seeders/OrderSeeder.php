<?php

namespace Database\Seeders;

use App\Enums\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'customer_id' => 1,
                'order_date' => now()->subDays(5),
                'status' => Status::COMPLETED->value,
                'total_amount' => 800000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 2,
                'order_date' => now()->subDays(4),
                'status' => Status::PROCESSING->value,
                'total_amount' => 300000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 3,
                'order_date' => now()->subDays(3),
                'status' => Status::SHIPPED->value,
                'total_amount' => 71000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 4,
                'order_date' => now()->subDays(2),
                'status' => Status::PENDING->value,
                'total_amount' => 16000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 5,
                'order_date' => now()->subDays(1),
                'status' => Status::DELIVERED->value,
                'total_amount' => 150000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
