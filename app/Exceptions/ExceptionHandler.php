<?php

namespace App\Exceptions;

use App\Traits\JsonResponseAPI;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ExceptionHandler
{
    use JsonResponseAPI;

    public function handle(Exceptions $exceptions): Exceptions
    {
        $exceptions->reportable(function (Throwable $e) {
            Log::error($e);
        });

        return $exceptions->render(function (Exception $e) {
            if ($e instanceof GuzzleException) {
                return $this->guzzleException($e);
            }
            if ($e instanceof ModelNotFoundException) {
                return $this->modelNotFoundException($e);
            }
            if ($e instanceof NotFoundHttpException) {
                return $this->notFoundException($e);
            }
            if ($e instanceof AuthenticationException) {
                return $this->authenticationException($e);
            }
            if ($e instanceof TooManyRequestsHttpException) {
                return $this->tooManyRequestsHttpException($e);
            }
            if ($e instanceof QueryException) {
                return $this->queryException($e);
            }
            if ($e instanceof RuntimeException){
                return $this->importException($e);
            }
            if (method_exists($e, 'render')) {
                return $e->render($e);
            }

            $message = !app()->environment('production')
                ? $e->getMessage()
                : 'There was a problem processing your request. Please check back later.';
            return response()->json([
                'success' => false,
                'message' => $message,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    }

    private function guzzleException(GuzzleException $e): JsonResponse
    {
        return $this->errorResponse(
            'Server Error: Issue communicating with third-party',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    private function modelNotFoundException(ModelNotFoundException $e): JsonResponse
    {
        return $this->errorResponse(
            'Requested Entity Not Found',
            Response::HTTP_NOT_FOUND
        );
    }

    private function authenticationException(AuthenticationException $e): JsonResponse
    {
        return $this->errorResponse(
            $e->getMessage(),
            Response::HTTP_UNAUTHORIZED
        );
    }

    private function notFoundException(NotFoundHttpException $e): JsonResponse
    {
        return $this->errorResponse(
            'This URL seems to be on a coffee break.',
            Response::HTTP_NOT_FOUND
        );
    }

    private function tooManyRequestsHttpException(TooManyRequestsHttpException $e): JsonResponse
    {
        return $this->errorResponse(
            'Too many attempts made. Kindly try again later',
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    private function queryException(QueryException $e): JsonResponse
    {
        Log::error('Database Query Exception: ', [$e]);

        if (!app()->environment('production')) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
        return $this->errorResponse(
            "We couldn't process your request right now.",
            Response::HTTP_BAD_REQUEST
        );
    }

    private function importException(RuntimeException $e): JsonResponse
    {
        Log::error('Runtime exception: ', [$e]);

        if (!app()->environment('production')) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->errorResponse(
            "Your request could not be processed right now.",
            Response::HTTP_BAD_REQUEST
        );
    }
}
