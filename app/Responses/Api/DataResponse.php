<?php

namespace App\Responses\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class DataResponse
{
    private ?string $message;
    private array|Collection|null $data;
    private int $status;

    public function __construct(?string $message = null, array|Collection|null $data = null, int $status = HTTPResponse::HTTP_OK)
    {
        $this->message = $message;
        $this->data = $data;
        $this->status = $status;
    }

    public function toJson(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'data' => $this->data,
        ], $this->status);
    }
}
