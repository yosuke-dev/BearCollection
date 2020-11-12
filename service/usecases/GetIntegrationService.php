<?php

namespace Service\UseCases;

use Service\Repositories\IntegrationServiceRepository;

class GetIntegrationService
{
    private $integration_service_repository;

    public function __construct() {
        $this->integration_service_repository = new IntegrationServiceRepository;
    }

    public function execute()
    {
        $integration_services = $this->integration_service_repository->all();
        return ['integration_services' => $integration_services];
    }
}
