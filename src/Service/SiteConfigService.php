<?php

namespace App\Service;

use App\Repository\SiteConfigRepository;

class SiteConfigService
{
    public function __construct(
        private SiteConfigRepository $configRepository
    ) {}

    public function isMaintenanceEnabled(): bool
    {
        return $this->configRepository->getValue('maintenance_enabled') === '1';
    }
}
