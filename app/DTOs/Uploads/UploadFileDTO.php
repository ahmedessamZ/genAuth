<?php

namespace App\DTOs\Uploads;

use Illuminate\Http\UploadedFile;

class UploadFileDTO
{
    public ?UploadedFile $file;
    public ?string $model_type;
    public ?string $model_id;
    public ?string $collection = 'default';

    public function __construct(array $data)
    {
        $this->file = $data['file'];
        $this->model_type = $data['model_type'];
        $this->model_id = $data['model_id'];
        $this->collection = $data['collection'] ?? 'default';
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function getModelType(): ?string
    {
        return $this->model_type;
    }

    public function getModelId(): ?string
    {
        return $this->model_id;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }
}
