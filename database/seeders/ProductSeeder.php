<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop',
                'sku' => 'LP111',
                'price' => 700000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Samsung',
                'sku' => 'SAM2002',
                'price' => 250000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Air Pods',
                'sku' => 'AP331',
                'price' => 70000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Shirt',
                'sku' => 'SH141',
                'price' => 15000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nike Air',
                'sku' => 'NA',
                'price' => 100000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
