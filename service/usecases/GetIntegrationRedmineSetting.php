<?php

namespace Service\UseCases;

use Service\Repositories\IntegrationRedmineSettingRepository;

class GetIntegrationRedmineSetting
{
    private $integration_redmine_setting_repository;

    public function __construct() {
        $this->integration_redmine_setting_repository = new IntegrationRedmineSettingRepository;
    }

    public function execute($integration_setting_id)
    {
        $integration_redmine_setting = $this->integration_redmine_setting_repository->getByIntegrationSettingId($integration_setting_id);
        return ['integration_redmine_setting' => $integration_redmine_setting];
    }
}
