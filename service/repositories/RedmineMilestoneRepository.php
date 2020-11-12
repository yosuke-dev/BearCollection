<?php

namespace Service\Repositories;
use App\Models\RedmineMilestone;

class RedmineMilestoneRepository extends BaseRepository
{
    public function __construct() {
    }

    public function getByMilestoneId($milestone_id)
    {
        return RedmineMilestone::whereMilestoneId($milestone_id)->firstOrFail();
    }

    public function register($data)
    {
        return RedmineMilestone::create($data);
    }
}
