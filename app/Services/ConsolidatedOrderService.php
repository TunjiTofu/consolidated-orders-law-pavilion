<?php

namespace App\Services;

use App\Exports\ConsolidatedOrdersExport;
use App\Imports\ConsolidatedOrdersImport;
use App\Traits\ServiceResponseTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConsolidatedOrderService
{
    use ServiceResponseTrait;

    /**
     * @param UploadedFile $file
     * @return array
     */
    public function importData(UploadedFile $file): array
    {
        Log::alert('Attempt to import data from ', [
            'fileName' => $file->getClientOriginalName(),
            'fileSize' => $file->getSize()
        ]);

        $import = new ConsolidatedOrdersImport();

        try {

            Excel::import($import, $file);
            $data = [
                'count' => $import->getRowCount(),
                'skipped' => $import->getSkippedRows()
            ];
            Log::alert('Data imported successfully', [$data]);
            return $this->serviceResponse('Data imported successfully!', true, $data);

        } catch (\Throwable $th) {

            $data = [
                'fileName' => $file->getClientOriginalName(),
                'fileSize' => $file->getSize(),
            ];
            Log::alert('Cannot upload data at this time', [$data]);
            return $this->serviceResponse($th->getMessage(), false, $data);
        }
    }


    /**
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function exportData(): BinaryFileResponse
    {
        Log::alert('Attempt to export data');

        $fileName = 'consolidated_orders_'.now()->format('Ymd_His').'.xlsx';
        $export = new ConsolidatedOrdersExport();
        Log::alert('Attempting file export', [
            'fileName' => $fileName,
        ]);
        try {

            $response = Excel::download($export, $fileName);
            Log::alert($fileName . ' file generated successfully', [
                'fileName' => $fileName,
                'fileSize' => $response->getFile()->getSize(),
                'recordCount' => $export->getRowCount(),
            ]);
            return $response;

        } Catch (\Throwable $th) {
            $data = [
                'fileName' => $fileName,
            ];
            Log::alert('Cannot export data at this time', [$data]);
            throw  new Exception('Failed to export file'. $th->getMessage());
        }
    }
}
