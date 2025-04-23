<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UploadsRepositoryInterface;

class UploadsRepository implements UploadsRepositoryInterface
{
    public function uploadMedia($model, $file, $collection = 'default')
    {
        $model->clearMediaCollection($collection);
        return $model->addMedia($file)->toMediaCollection($collection);
    }
}
