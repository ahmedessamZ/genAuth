<?php

namespace App\Http\Resources;

use App\Enums\UserStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => "$this->country_code-$this->phone",
            'status' => UserStatusEnum::tryFrom($this->status)->getLabel(),
            'avatar' => $this->getFirstMedia('user-avatar') ?? null
        ];

        return $data;
    }

}
