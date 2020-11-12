<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Service\UseCases\GetEarnedSchedule;

class EarnedScheduleController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $input = new GetEarnedSchedule;
        $data = $input->execute();
        return Inertia::render('EarnedSchedule/Index', ['projects' => $data["projects"], 'milestones' => $data["milestones"]]);
    }
}
