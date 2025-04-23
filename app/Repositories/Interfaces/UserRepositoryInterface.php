<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByPhone(string $phone,string $countryCode): ?User;
    public function storeOtp($otp,$user);
    public function verifyOtp($user,$otp): bool;
    public function createToken($loginDto, $user): string;
    public function register($registerDto);
    public function completeProfile($completeProfileDto);
    public function getAuthUser();
    public function updateProfile($completeProfileDto);
    public function deleteUser(): bool;
}
