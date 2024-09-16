<?php

namespace CMW\Permissions\Simplecookies;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'simplecookies.manage',
                description: LangManager::translate('simplecookies.permissions.manage'),
            ),
        ];
    }
}
