<?php declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\Enum\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['string', 'required', 'email', Rule::unique('users', 'email')],
            'password' => ['string', 'required', Password::min(5)->numbers(), 'confirmed'],
            'role' => ['required', Rule::enum(UserRoleEnum::class)],
        ];
    }
}
