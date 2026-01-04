<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success(string $message, mixed $data = null, int $status = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        // If ResourceCollection (pagination-friendly)
        if ($data instanceof ResourceCollection) {
            $array = $data->response()->getData(true);

            $response['data'] = $array['data'];
            $response['meta'] = $array['meta'] ?? null;
            $response['links'] = $array['links'] ?? null;
        }
        // If paginator directly
        elseif ($data instanceof LengthAwarePaginator) {
            $response['data'] = $data->items();
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ];
            $response['links'] = [
                'next' => $data->nextPageUrl(),
                'prev' => $data->previousPageUrl(),
            ];
        }
        // Normal data
        elseif (!is_null($data)) {
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
