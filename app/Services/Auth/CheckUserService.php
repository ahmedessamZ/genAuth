<?php

namespace App\Services\Auth;

use App\DTOs\Auth\CheckUserDTO;
use App\Models\User;
use App\Repositories\UserRepository;

class CheckUserService {
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function checkUser(CheckUserDTO $dto): ?User
    {
        return $this->userRepository->findByPhone($dto->getPhone(),$dto->getCountryCode());
    }

}
