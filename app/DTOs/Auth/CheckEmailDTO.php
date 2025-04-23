<?php

namespace App\DTOs\Auth;

class CheckEmailDTO
{
    public ?string $email;
    public ?string $otp;


    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->otp = $data['otp'] ?? null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getOtp(): ?string
    {
        return $this->otp;
    }
}
