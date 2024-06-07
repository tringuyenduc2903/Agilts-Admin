<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The Permission enum.
 */
class Permission extends Enum
{
    const DASHBOARD = 'dashboard';
    const ADMIN_CRUD = 'admin_crud';
    const ROLE_CRUD = 'role_crud';
    const PERMISSION_CRUD = 'permission_crud';
    const CUSTOMER_CRUD = 'customer_crud';
}
