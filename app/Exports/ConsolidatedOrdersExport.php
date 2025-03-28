<?php

namespace App\Exports;

use App\Models\ConsolidatedOrder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsolidatedOrdersExport implements FromCollection, WithHeadings, WithChunkReading
{
    private int $rowCount = 0;

    /**
     * @return Collection|\Illuminate\Support\Collection
     */
    public function collection(): Collection|\Illuminate\Support\Collection
    {
        $data = ConsolidatedOrder::all();
        $this->rowCount = $data->count();
        return $data;
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID', 'Order ID', 'Customer ID', 'Customer Name', 'Customer Email',
            'Product ID', 'Product Name', 'SKU', 'Quantity', 'Item Price',
            'Line Total', 'Order Date', 'Order Status', 'Order Total',
            'Created At', 'Updated At'
        ];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 10000;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
