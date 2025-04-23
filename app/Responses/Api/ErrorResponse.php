<?php

namespace App\Responses\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Throwable;

class ErrorResponse
{
    private string $message;
    private int $status;


    public function __construct(string $message, int $status = HTTPResponse::HTTP_BAD_REQUEST)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public function toJson(Throwable $exception = null): JsonResponse
    {
        if ($exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => $this->message,
            'errors' => $exception?->getMessage(),
            'status' => $this->status,
        ], $this->status);
    }
}
