<?php

namespace App\Helper;

use Exception;
use Illuminate\Http\JsonResponse;

class ResponseContent
{
    public static function getResponse($data, $title, $message): array
    {
        return [
            'data' => $data,
            'title' => $title,
            'message' => $message
        ];
    }

    public static function getServerError($e = null): JsonResponse
    {
        return response()->json([
            'error' => config('error.message'),
            'exception' => $e
        ], 500);
    }
}
