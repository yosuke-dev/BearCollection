<?php

namespace Service\Repositories;
use App\Models\IntegrationService;

class IntegrationServiceRepository extends BaseRepository
{
    public function __construct() {
    }

    public function all()
    {
        $all = IntegrationService::all();
        return $all;
    }
}
