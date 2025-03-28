<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseAPI
{
    /**
     * Returns a successful response with a status code
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse(
        string $message = 'Success',
        $data = null,
        int $statusCode = Response::HTTP_OK,
    ): JsonResponse {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            $statusCode
        );
    }

    /**
     * This returns an error message back to the client with a status code
     *
     * @param string $message
     * @param int $statusCode
     * @param array $headers
     * @param mixed ...$metas
     * @return JsonResponse
     */
    public function errorResponse(
        string $message = 'Not found',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $headers = [],
        ...$metas
    ): JsonResponse
    {
        return response()->json(array_merge(
            [
                'success'  => false,
                'message' => $message
            ], $metas),
            $statusCode,
            $headers
        );
    }

    /**
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function internalErrorResponse(?string $message = null): JsonResponse
    {
        return $this->errorResponse($message ?? "A System error has occurred.", Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     *
     * @param string|null $message
     * @return JsonResponse
     */
    public function clientErrorResponse(?string $message = null): JsonResponse
    {
        return $this->errorResponse($message ?? "Bad Request.", Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function noRecords(string $message = "There are no records at the moment."): JsonResponse
    {
        return $this->errorResponse($message);
    }
}
