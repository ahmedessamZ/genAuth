<?php

namespace App\Repositories;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\VerificationCode;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Laravel\Sanctum\PersonalAccessToken;

class UserRepository  implements UserRepositoryInterface
{
    public function findByPhone(string $phone,string $countryCode): ?User
    {
        return User::query()
            ->where('phone', $phone)
            ->where('country_code', $countryCode)
            ->first();
    }

    public function storeOtp($otp,$user): void
    {
         VerificationCode::query()->updateOrCreate(
             ['user_id' => $user->id],
             ['code' => $otp],
             ['exists' => true]
         );
    }

    public function verifyOtp($user,$otp): bool
    {
        $currentOtp = VerificationCode::query()
            ->where('user_id' , $user->id)
            ->latest()
            ->first();
        return $currentOtp?->code === $otp && $currentOtp->delete();
    }

    public function createToken($loginDto, $user): string
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        $tokenObject = PersonalAccessToken::findToken($token);
        UserDevice::query()
            ->updateOrInsert(
                [
                    'device_id' => $loginDto->getDeviceId(),
                    'user_id' => $user->id,
                ],
                [
                    'token_id' => $tokenObject->id,
                    'device_type' => $loginDto->getDeviceType(),
                    'fcm_token' => $loginDto->getFcmToken(),
                ]
            );
        return $token;
    }

    public function register($registerDto)
    {
        return User::query()->create([
            'country_code' => $registerDto->getCountryCode(),
            'phone' => $registerDto->getPhone(),
            'status' => UserStatusEnum::PENDING,
        ])->fresh();
    }

    public function completeProfile($completeProfileDto)
    {
        $user = auth()->user();

        $updated = $user->update([
            'name' => $completeProfileDto->getName(),
            'email' => $completeProfileDto->getEmail(),
            'status' =>UserStatusEnum::ACTIVE
        ]);

        return $updated ? $user->fresh() : null;
    }

    public function getAuthUser()
    {
        return auth()->user()->fresh();
    }

    public function updateProfile($completeProfileDto)
    {
        $user = auth()->user();

        $updated = $user->update([
            'name' => $completeProfileDto->getName(),
            'email' => $completeProfileDto->getEmail(),
            'country_code' => $completeProfileDto->getCountryCode(),
            'phone' => $completeProfileDto->getPhone(),
            'status' =>UserStatusEnum::ACTIVE
        ]);

        return $updated ? $user->fresh() : null;
    }

    public function deleteUser(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $user->tokens()->delete();
        $user->devices()->delete();
        return $user->delete();
    }
}
