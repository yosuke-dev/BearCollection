<?php

namespace Service\Repositories;
use App\Models\IntegrationSetting;

class IntegrationSettingRepository extends BaseRepository
{
    public function __construct() {
    }

    public function all()
    {
        $all = IntegrationSetting::with('integrationService')->get();
        return $all;
    }
}
