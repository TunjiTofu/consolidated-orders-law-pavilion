<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'John Mark',
                'email' => 'john@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Jane Adeola',
                'email' => 'jane@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Femi Wilson',
                'email' => 'femi@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Alice Chidi',
                'email' => 'alice@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mike Brown',
                'email' => 'mike@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
