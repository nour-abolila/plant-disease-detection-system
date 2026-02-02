<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(string $message = 'Success', $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public static function error(string $message = 'Error', $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
