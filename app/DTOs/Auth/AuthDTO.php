<?php

namespace App\DTOs\Auth;

class AuthDTO
{
    public ?string $phone;
    public ?string $country_code;
    public ?string $device_type;
    public ?string $device_id;
    public ?string $fcm_token;

    public function __construct(array $data)
    {
        $this->phone = $data['phone'];
        $this->country_code = $data['country_code'];
        $this->device_type = $data['device_type'];
        $this->device_id = $data['device_id'];
        $this->fcm_token = $data['fcm_token'];
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function GetDeviceType(): ?string
    {
        return $this->device_type;
    }

    public function GetDeviceId(): ?string
    {
        return $this->device_id;
    }

    public function GetFcmToken(): ?string
    {
        return $this->fcm_token;
    }
}
