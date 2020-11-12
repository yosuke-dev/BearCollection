<?php

namespace Service\Repositories;
use App\Models\Milestone;

class MilestoneRepository extends BaseRepository
{
    public function __construct() {
    }

    public function all()
    {
        return Milestone::all();
    }

    public function get($id)
    {
        return Milestone::find($id);
    }

    public function getByProjectId($project_id)
    {
        return Milestone::whereProjectId($project_id);
    }

    public function register($data)
    {
        return Milestone::create($data);
    }
}
