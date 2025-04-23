<?php

namespace App\Http\Controllers\Api\V1\User;

use App\DTOs\User\UpdateUserDTO;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Responses\Api\DataResponse;
use App\Responses\Api\ErrorResponse;
use App\Services\User\DeleteUserService;
use App\Services\User\ShowUserService;
use App\Services\User\UpdateUserService;
use Illuminate\Http\JsonResponse;
use Throwable;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class UserController
{
    private ShowUserService $showUserService;
    private UpdateUserService $updateUserService;
    private DeleteUserService $deleteUserService;


    public function __construct
    (
        ShowUserService   $showUserService,
        UpdateUserService $updateUserService,
        DeleteUserService $deleteUserService,
    )
    {
        $this->showUserService = $showUserService;
        $this->updateUserService = $updateUserService;
        $this->deleteUserService = $deleteUserService;
    }


    public function show(): JsonResponse
    {
        try {
            $user = $this->showUserService->execute();
            return (new DataResponse('Data Fetched successfully',
                [
                    'user' => new UserResource($user),
                ]
            ))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Something went wrong',
                HTTPResponse::HTTP_BAD_REQUEST,
            ))->toJson($exception);
        }

    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        try {
            $updateUserDto = new UpdateUserDTO($request->only('name', 'email', 'country_code', 'phone'));
            $updatedUser = $this->updateUserService->execute($updateUserDto);

            return (new DataResponse('Profile updated successfully',
                [
                    'user' => new UserResource($updatedUser),
                ]
            ))->toJson();

        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Something went wrong',
                HTTPResponse::HTTP_BAD_REQUEST,
            ))->toJson($exception);
        }
    }

    public function delete(): JsonResponse
    {
        try {
            $delete = $this->deleteUserService->execute();
            if ($delete) {
                return (new DataResponse('Deleted Successfully',))->toJson();
            } else {
                return (new DataResponse('Failed to delete',))->toJson();
            }
        } catch (Throwable $exception) {
            return (new ErrorResponse(
                'Something went wrong',
                HTTPResponse::HTTP_BAD_REQUEST,
            ))->toJson($exception);
        }
    }

}
