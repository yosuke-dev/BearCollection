<?php

namespace Service\UseCases;

use Service\Repositories\ProjectRepository;
use Service\Repositories\MilestoneRepository;

class GetEarnedSchedule
{
    private $projectRepository;
    private $milestoneRepository;

    public function __construct() {
        $this->projectRepository = new ProjectRepository;
        $this->milestoneRepository = new MilestoneRepository;
    }

    public function execute()
    {
        $projects = $this->projectRepository->all();
        $milestones = $this->milestoneRepository->all();
        return ['projects' => $projects, 'milestones' => $milestones];
    }
}
