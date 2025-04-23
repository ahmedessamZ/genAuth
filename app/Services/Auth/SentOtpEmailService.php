<?php

namespace App\Services\Auth;

use App\Notifications\SendEmailOtpNotification;
use Illuminate\Support\Facades\Notification;
use App\Repositories\UserRepository;

class SentOtpEmailService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendEmailOtp($verifyEmailDto): int
    {
        $otp = rand(100000, 999999);
        $user = auth()->user();
        $this->userRepository->storeOtp($otp, $user);
        $recipientEmail = $verifyEmailDto->getEmail();
        Notification::route('mail', $recipientEmail)->notify(new SendEmailOtpNotification($otp));
        return $otp;
    }

}
