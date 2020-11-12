<?php

namespace Service\UseCases;

use Service\Repositories\EarnedScheduleRepository;

class GetEarnedScheduleCost
{
    private $earnedScheduleRepository;

    public function __construct() {
        $this->earnedScheduleRepository = new EarnedScheduleRepository;
    }

    public function execute($milestone_id)
    {
        $earnedschedules = $this->earnedScheduleRepository->getByMilestoneId($milestone_id)->get()->toArray();
        return ['earnedschedules' => $earnedschedules];
    }
}
