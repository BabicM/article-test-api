<?php declare(strict_types=1);

namespace App\Models\Enum;

enum UserRoleEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}
