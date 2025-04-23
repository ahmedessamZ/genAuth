<?php

namespace App\Services\User;

use App\Repositories\UserRepository;

class DeleteUserService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): bool
    {
        return $this->userRepository->deleteUser();

    }

}
