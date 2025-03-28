<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportConsolidatedDataRequest;
use App\Services\ConsolidatedOrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConsolidatedOrderController extends Controller
{
    /**
     * @param ConsolidatedOrderService $consolidatedOrderService
     */
    public function __construct(protected ConsolidatedOrderService $consolidatedOrderService)
    {}

    /**
     * @param ImportConsolidatedDataRequest $request
     * @return JsonResponse
     */
    public function importData(ImportConsolidatedDataRequest $request): JsonResponse
    {
        try {
            $form = $request->validated();
            $importResponse = $this->consolidatedOrderService->importData($form['file']);

            if (!$importResponse['success']) {
                Log::alert($importResponse['message']);
                return $this->errorResponse($importResponse['message'], $importResponse['status']);
            }

            return $this->successResponse($importResponse['message'], $importResponse['data']);

        } catch (Exception $e) {
            Log::alert('Error: ' . $e->getMessage());
            return $this->internalErrorResponse('An unexpected error occurred during upload of consolidated data - '. $e->getMessage());
        }

    }

    /**
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportData(): BinaryFileResponse|JsonResponse
    {
        try {
            Log::alert('Starting consolidated data export');
           $exportResponse = $this->consolidatedOrderService->exportData();

            Log::info('Consolidated data exported successfully', [
                'file_name' => $exportResponse->getFile()->getFilename(),
                'file_size' => $exportResponse->getFile()->getSize(),
            ]);

            return $exportResponse;

        } catch (Exception $e) {
            Log::alert('Error: ' . $e->getMessage());
            return $this->internalErrorResponse('An unexpected error occurred during export of consolidated data');
        }
    }
}
