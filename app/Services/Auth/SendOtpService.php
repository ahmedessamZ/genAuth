<?php

namespace App\Services\Auth;

use App\Notifications\SendOtpNotification;
use App\Repositories\UserRepository;

class SendOtpService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendOtp($user): int
    {
        $otp = rand(100000, 999999);
        $this->userRepository->storeOtp($otp,$user);
        $user->notify(new SendOtpNotification($otp));
        return $otp;
    }

}
