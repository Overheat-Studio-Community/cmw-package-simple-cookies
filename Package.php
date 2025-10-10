<?php

namespace CMW\Package\SimpleCookies;

use CMW\Manager\Package\IPackageConfigV2;
use CMW\Manager\Package\PackageMenuType;

class Package implements IPackageConfigV2
{
    public function name(): string
    {
        return 'SimpleCookies';
    }

    public function version(): string
    {
        return '1.1.0';
    }

    public function authors(): array
    {
        return ['OverheatStudio'];
    }

    public function isGame(): bool
    {
        return false;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                icon: 'fas fa-cookie',
                title: 'Simple Cookies',
                url: 'cookies',
                permission: 'cookies.manage'
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }

    public function cmwVersion(): string
    {
        return "beta-01";
    }

    public function imageLink(): ?string
    {
        return null;
    }

    public function author(): ?string
    {
        return 'OverheatStudio';
    }

    public function compatiblesPackages(): array
    {
        return [];
    }
}
