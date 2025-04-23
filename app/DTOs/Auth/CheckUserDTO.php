<?php

namespace App\DTOs\Auth;

class CheckUserDTO
{
    public ?string $phone;
    public ?string $country_code;

    public ?string $otp;

    public function __construct(array $data)
    {
        $this->phone = $data['phone'];
        $this->country_code = $data['country_code'];
        $this->otp = $data['otp'] ?? null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }
}
