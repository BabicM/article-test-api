<?php declare(strict_types=1);

namespace App\Http\Resources\Dto;

use App\Http\Resources\DtoAbstract;
use App\Models\Enum\UserRoleEnum;
use Illuminate\Support\Carbon;

class UserDto extends DtoAbstract
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
        public UserRoleEnum $userRoleEnum,
        public ?Carbon $email_verified_at = null
    )
    {

    }

}
