<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Http\Resources\Dto\UserDto;
use App\Http\Resources\UserResource;
use App\Models\Enum\UserRoleEnum;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    )
    {
        //
    }

    public function register(RegistrationRequest $registrationRequest): UserResource
    {
        $userDto = new UserDto(
            email: $registrationRequest->validated('email'),
            name: $registrationRequest->validated('name'),
            password: $registrationRequest->validated('password'),
            userRoleEnum: UserRoleEnum::from($registrationRequest->validated('role')),
            email_verified_at: now(),
        );

        $userModel = $this->userService->registerUser($userDto);

        return new UserResource($userModel);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $newAccessToken = $this->userService->login(
            email: $request->validated('email'),
            password: $request->validated('password')
        );

        return response()->json([
            'data' => [
                'token' => $newAccessToken->plainTextToken,
                'expires' => $newAccessToken->accessToken->expires_at
            ],
        ]);
    }

    public function me(): UserResource
    {
        return new UserResource(auth()->user());
    }

    public function getAvailableRoles(): JsonResponse
    {
        return response()->json(
            [
                'data' => UserRoleEnum::cases()
            ]
        );
    }
}
