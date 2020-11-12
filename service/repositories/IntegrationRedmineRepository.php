<?php

namespace Service\Repositories;
use App\Models\IntegrationRedmineSetting;

class IntegrationRedmineSettingRepository extends BaseRepository
{
    public function __construct() {
    }

    public function getByIntegrationSettingId($integration_setting_id)
    {
        $integration_redmine_setting = IntegrationRedmineSetting::whereIntegrationSettingId($integration_setting_id)->firstOrFail();
        return $integration_redmine_setting;
    }
}
