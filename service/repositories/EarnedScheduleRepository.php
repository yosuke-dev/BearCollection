<?php

namespace Service\Repositories;
use App\Models\EarnedSchedule;

class EarnedScheduleRepository extends BaseRepository
{
    public function __construct() {
    }

    public function getByMilestoneId($milestone_id)
    {
        $earnedschedules = EarnedSchedule::whereMilestoneId($milestone_id);
        return $earnedschedules;
    }

    public function register($data)
    {
        return EarnedSchedule::create($data);
    }

    public function destroyByMilestoneId($milestone_id)
    {
        $earned_schedules = EarnedSchedule::whereMilestoneId($milestone_id)->get();
        foreach ($earned_schedules as $earned_schedule) {
            $earned_schedule->delete();
        }
    }
}
