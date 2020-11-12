<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Service\UseCases\GetEarnedScheduleCost;
use Service\UseCases\ReCreateEarnedScheduleCost;

class EarnedScheduleCostController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function index($milestone_id)
    {
        $input = new GetEarnedScheduleCost;
        $data = $input->execute($milestone_id);
        return ['earnedschedules' => $data["earnedschedules"]];
    }

    /**
     * @return Request
     */
    public function update($milestone_id)
    {
        $usecase = new ReCreateEarnedScheduleCost($milestone_id);
        $usecase->execute();
        return;
    }
}
