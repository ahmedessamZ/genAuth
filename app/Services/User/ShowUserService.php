<?php

namespace App\Services\User;

use App\Repositories\UserRepository;

class ShowUserService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute()
    {
        return $this->userRepository->getAuthUser();

    }

}
