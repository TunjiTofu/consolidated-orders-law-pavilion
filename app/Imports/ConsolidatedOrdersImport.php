<?php

namespace App\Imports;

use App\Models\ConsolidatedOrder;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ConsolidatedOrdersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    private int $processed = 0;
    private int $skipped = 0;

    /**
     * @param array $row
     * @return ConsolidatedOrder|null
     */
    public function model(array $row): ?ConsolidatedOrder
    {
        Log::alert('Importing row: ', $row);

        $this->processed++;

        return new ConsolidatedOrder([
            'id'            => $row['id'] ?? null,
            'order_id'      => $row['order_id'],
            'customer_id'   => $row['customer_id'],
            'customer_name' => $row['customer_name'] ?? '',
            'customer_email'=> $row['customer_email'] ?? '',
            'product_id'   => $row['product_id'],
            'product_name' => $row['product_name'] ?? '',
            'sku'          => $row['sku'] ?? '',
            'quantity'    => $row['quantity'],
            'item_price'   => $row['item_price'],
            'line_total'   => $row['line_total'] ?? ($row['quantity'] * $row['item_price']),
            'order_date'   => $row['order_date'],
            'order_status' => $row['order_status'] ?? 'pending',
            'order_total'  => $row['order_total'] ?? 0,
        ]);

    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'item_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * @return string
     */
    public function uniqueBy(): string
    {
        return 'id';
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @param \Throwable $e
     * @return void
     */
    public function onError(\Throwable $e): void
    {
        $this->skipped++;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->processed - $this->skipped;
    }

    /**
     * @return int
     */
    public function getSkippedRows(): int
    {
        return $this->skipped;
    }
}
