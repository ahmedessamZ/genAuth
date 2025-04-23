<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;

class VerifyOtpEmailService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function verifyEmailOtp($verifyEmailDto): bool
    {
        $otp = $verifyEmailDto->getOtp();
        $user = auth()->user();
        $user->update(['email_verified_at'=>Carbon::now()]);
        return $this->userRepository->verifyOtp($user,$otp);
    }
}
