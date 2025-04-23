<?php

namespace App\DTOs\User;

class UpdateUserDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $country_code;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->country_code = $data['country_code'];
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }
}
