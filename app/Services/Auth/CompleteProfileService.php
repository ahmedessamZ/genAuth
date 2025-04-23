<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;

class CompleteProfileService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function completeProfile($completeProfileDto)
    {
        return $this->userRepository->completeProfile($completeProfileDto);
    }

}
