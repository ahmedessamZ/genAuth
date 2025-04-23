<?php

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:10240',
            'model_type' => 'required|string',
            'model_id' => 'required|string',
            'collection' => 'required|string',
        ];
    }
}
