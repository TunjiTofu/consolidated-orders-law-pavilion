<?php

namespace App\Traits;

trait ServiceResponseTrait
{
    /**
     * @param string $message
     * @param bool $success
     * @param $data
     * @return array
     */
    protected function serviceResponse(string $message, bool $success = false, $data = null): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];
    }
}
