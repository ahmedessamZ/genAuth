<?php

namespace App\Services\Uploads;

use App\DTOs\Uploads\UploadFileDTO;
use App\Repositories\UploadsRepository;

class UploadFileService
{
    private UploadFileDTO $dto;
    private UploadsRepository $uploadsRepository;
    private $file;

    public function __construct(UploadsRepository $uploadsRepository)
    {
        $this->uploadsRepository = $uploadsRepository;
    }

    public function setDTO(UploadFileDTO $dto): self
    {
        $this->dto = $dto;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function execute(): void
    {
        $file = $this->dto->getFile();
        $modelClass = $this->dto->getModelType();
        $model = app($modelClass)::findOrFail($this->dto->getModelId());
        $collection = $this->dto->getCollection();
        $this->file = $this->uploadsRepository->uploadMedia($model, $file, $collection);
    }
}
