<?php

namespace App\Enum;

enum UserRoleEnum:string
{
    case ADMIN = 'admin';
    case ATHOR = 'author';
    case GUEST = 'guest';
}

