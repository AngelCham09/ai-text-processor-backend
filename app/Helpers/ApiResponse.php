<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(string $message, mixed $data = null, int $status = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    public static function error(string $message, mixed $data = null, int $status = 400): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['errors'] = $data;
        }

        return response()->json($response, $status);
    }
}
