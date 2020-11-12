<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IntegrationService;
use Service\UseCases\GetIntegrationService;

class IntegrationServiceController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $input = new GetIntegrationService;
        $data = $input->execute();
        return $data['integration_services'];
    }
}
