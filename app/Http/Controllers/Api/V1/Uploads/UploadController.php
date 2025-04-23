<?php

namespace App\Http\Controllers\Api\V1\Uploads;


use App\DTOs\Uploads\UploadFileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Uploads\UploadFileRequest;
use App\Responses\Api\DataResponse;
use App\Responses\Api\ErrorResponse;
use App\Services\Uploads\UploadFileService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Throwable;

class UploadController extends Controller
{
    private UploadFileService $fileService;

    public function __construct(UploadFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(UploadFileRequest $request): JsonResponse
    {
        try {
            $dto = new UploadFileDTO($request->only('file','model_type','model_id','collection'));

            $fileService = $this->fileService->setDTO($dto);
            $fileService->execute();
            $mediaData = $fileService->getFile()->toArray();

            return (new DataResponse(
                'successfully uploaded',
                $mediaData,
            ))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Something went wrong',
                HTTPResponse::HTTP_BAD_REQUEST,
            ))->toJson($exception);
        }
    }
}
