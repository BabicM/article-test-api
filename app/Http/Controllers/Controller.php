<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function authorizeAction(UserRoleEnum ...$actionIsAvailableFor): void
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(
            boolean: !isset($user),
            code: 403,
            message: 'This action is not available for guest. Please sign in.'
        );

        if ($user->getIsAdminAttribute()) {
            return;
        }

        foreach ($actionIsAvailableFor as $userRoleEnum) {
            if ($userRoleEnum->value === $user->role) {
                return;
            }
        }

        abort(
            code: 403,
            message: 'This action is not available for this user role.'
        );
    }
}
