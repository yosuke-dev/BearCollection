<?php

namespace Service\UseCases;

use Service\Repositories\IntegrationSettingRepository;

class GetIntegrationSetting
{
    private $integration_setting_repository;

    public function __construct() {
        $this->integration_setting_repository = new IntegrationSettingRepository;
    }

    public function execute()
    {
        $integration_settings = $this->integration_setting_repository->all();
        return ['integration_settings' => $integration_settings];
    }
}
