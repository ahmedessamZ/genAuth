<?php

namespace App\Services\User;

use App\Repositories\UserRepository;

class UpdateUserService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($updateUserDto)
    {
        return $this->userRepository->updateProfile($updateUserDto);
    }
}
