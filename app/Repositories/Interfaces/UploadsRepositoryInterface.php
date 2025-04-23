<?php

namespace App\Repositories\Interfaces;

interface UploadsRepositoryInterface
{
    public function uploadMedia($model, $file, $collection = 'default');
}
