<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\Dto\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

final class UserService
{
    public function registerUser(UserDto $userDto): User
    {
        $data = $userDto->toArray();
        $data['role'] = $userDto->userRoleEnum->value;

        return User::query()->create($data);
    }

    /**
     * @param string $email
     * @param string $password
     * @return NewAccessToken
     */
    public function login(string $email, string $password): NewAccessToken
    {
        /** @var User $user */
        $user = User::query()->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken(
            name: $user->email . '-token',
            expiresAt: now()->addMinutes(config('sanctum.expiration'))
        );
    }
}
