<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;

class RegisterService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($registerDto)
    {
        return $this->userRepository->register($registerDto);
    }

}
