<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Service\UseCases\GetGanttMilestone;

class GanttMilestoneController extends Controller
{
    /**
     * show function
     *
     * @param [int] $milestone_id
     * @return void
     */
    public function show($milestone_id)
    {
        $input = new GetGanttMilestone($milestone_id);
        $data = $input->execute();
        return $data;
    }
}
