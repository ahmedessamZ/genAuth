<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'country_code' => 'required|numeric|max:99999',
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
        ];
    }

}
