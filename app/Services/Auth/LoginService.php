<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;

class LoginService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($loginDto, $user): string
    {
        return $this->userRepository->createToken($loginDto, $user);
    }

}
