<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\CheckEmailDTO;
use App\DTOs\Auth\CheckUserDTO;
use App\DTOs\Auth\AuthDTO;
use App\DTOs\Auth\CompleteProfileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckUserRequest;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\CompleteProfileRequest;
use App\Http\Requests\Auth\VerifyEmailOtpRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Responses\Api\DataResponse;
use App\Responses\Api\ErrorResponse;
use App\Services\Auth\CheckUserService;
use App\Services\Auth\CompleteProfileService;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\SendOtpService;
use App\Services\Auth\SentOtpEmailService;
use App\Services\Auth\VerifyOtpEmailService;
use App\Services\Auth\VerifyOtpService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Throwable;

class AuthenticateController extends Controller
{
    private CheckUserService $checkUserService;
    private SendOtpService $sendOtpService;
    private VerifyOtpService $verifyOtpService;
    private LoginService $loginService;
    private RegisterService $registerService;
    private CompleteProfileService $completeProfileService;
    private SentOtpEmailService $sentOtpEmailService;
    private VerifyOtpEmailService $verifyOtpEmailService;


    public function __construct
    (
        CheckUserService       $checkUserService,
        SendOtpService         $sendOtpService,
        VerifyOtpService       $verifyOtpService,
        LoginService           $loginService,
        RegisterService        $registerService,
        CompleteProfileService $completeProfileService,
        SentOtpEmailService    $sentOtpEmailService,
        VerifyOtpEmailService  $verifyOtpEmailService,
    )
    {
        $this->checkUserService = $checkUserService;
        $this->sendOtpService = $sendOtpService;
        $this->verifyOtpService = $verifyOtpService;
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->completeProfileService = $completeProfileService;
        $this->sentOtpEmailService = $sentOtpEmailService;
        $this->verifyOtpEmailService = $verifyOtpEmailService;
    }


    public function checkUser(CheckUserRequest $request): JsonResponse
    {
        try {
            $dto = new CheckUserDTO($request->only('country_code', 'phone'));
            $user = $this->checkUserService->checkUser($dto);

            if ($user) {
                return (new DataResponse('User exists.', ['user_exists' => true]))->toJson();
            } else {
                return (new DataResponse('User does not exist.', ['user_exists' => false]))->toJson();
            }

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Something went wrong',
                HTTPResponse::HTTP_BAD_REQUEST,
            ))->toJson($exception);
        }
    }

    public function sendOtp(CheckUserRequest $request): JsonResponse
    {
        try {
            $dto = new CheckUserDTO($request->only('country_code', 'phone'));
            $user = $this->checkUserService->checkUser($dto);

            if (!$user) {
                return (new ErrorResponse(
                    'User does not exist.',
                    HTTPResponse::HTTP_NOT_FOUND
                ))->toJson();
            }

            $otp = $this->sendOtpService->sendOtp($user);

            return (new DataResponse('OTP sent successfully.', ['otp' => $otp]))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to send OTP.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $dto = new CheckUserDTO($request->only('country_code', 'phone', 'otp'));
            $user = $this->checkUserService->checkUser($dto);

            if (!$user) {
                return (new ErrorResponse(
                    'User does not exist.',
                    HTTPResponse::HTTP_NOT_FOUND
                ))->toJson();
            }

            $check = $this->verifyOtpService->verifyOtp($dto, $user);

            if ($check) {
                return (new DataResponse('Verified Successfully',))->toJson();
            } else {
                return (new DataResponse('Wrong OTP',))->toJson();
            }

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to verify OTP.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $checkUserDto = new CheckUserDTO($request->only('country_code', 'phone', 'otp'));
            $user = $this->checkUserService->checkUser($checkUserDto);

            if (!$user) {
                return (new ErrorResponse(
                    'User does not exist please register.',
                    HTTPResponse::HTTP_NOT_FOUND
                ))->toJson();
            }

            $loginDto = new AuthDTO($request->only('country_code', 'phone', 'device_type', 'device_id', 'fcm_token'));

            $userToken = $this->loginService->login($loginDto, $user);

            return (new DataResponse('Logged-in successfully',
                [
                    'user' => new UserResource($user),
                    'token' => $userToken
                ]
            ))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to login.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function register(AuthRequest $request): JsonResponse
    {
        try {
            $checkUserDto = new CheckUserDTO($request->only('country_code', 'phone', 'otp'));
            $user = $this->checkUserService->checkUser($checkUserDto);

            if ($user) {
                return (new ErrorResponse(
                    'User already exist.',
                    HTTPResponse::HTTP_NOT_FOUND
                ))->toJson();
            }

            $registerDto = new AuthDTO($request->only('country_code', 'phone', 'device_type', 'device_id', 'fcm_token'));

            $newUser = $this->registerService->register($registerDto);

            return (new DataResponse('registered successfully',
                [
                    'user' => new UserResource($newUser),
                ]
            ))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to register.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function completeProfile(CompleteProfileRequest $request): JsonResponse
    {
        try {
            $completeProfileDto = new CompleteProfileDto($request->only('name', 'email'));
            $CompleteProfile = $this->completeProfileService->completeProfile($completeProfileDto);
            return (new DataResponse('Profile completed successfully',
                [
                    'user' => new UserResource($CompleteProfile),
                ]
            ))->toJson();
        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to complete profile.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function sendOtpEmail(VerifyEmailRequest $request): JsonResponse
    {
        try {
            $verifyEmailDto = new CheckEmailDTO($request->only('email'));
            $otp = $this->sentOtpEmailService->sendEmailOtp($verifyEmailDto);

            return (new DataResponse('OTP sent successfully to Your Email.',
                ['otp' => $otp]))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to send OTP.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }

    public function verifyEmail(VerifyEmailOtpRequest $request): JsonResponse
    {
        try {
            $verifyEmailDto = new CheckEmailDTO($request->only('otp'));
            $check = $this->verifyOtpEmailService->verifyEmailOtp($verifyEmailDto);

            if ($check) {
                return (new DataResponse('Email Verified Successfully',))->toJson();
            } else {
                return (new DataResponse('Wrong OTP',))->toJson();
            }

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Failed to verify Email.',
                HTTPResponse::HTTP_INTERNAL_SERVER_ERROR,
            ))->toJson($exception);
        }
    }
}
