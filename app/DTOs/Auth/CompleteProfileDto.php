<?php

namespace App\DTOs\Auth;

class CompleteProfileDto
{
    public ?string $name;
    public ?string $email;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

}
