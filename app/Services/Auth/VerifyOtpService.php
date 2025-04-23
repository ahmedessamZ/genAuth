<?php

namespace App\Services\Auth;

use App\DTOs\Auth\CheckUserDTO;
use App\Models\User;
use App\Repositories\UserRepository;

class VerifyOtpService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function verifyOtp($dto,$user): bool
    {
        $otp = $dto->getOtp();
        return $this->userRepository->verifyOtp($user,$otp);
    }

}
