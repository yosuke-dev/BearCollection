<?php

namespace Service\Repositories;
use App\Models\Project;

class ProjectRepository extends BaseRepository
{
    public function __construct() {
    }

    public function all()
    {
        $all = Project::all();
        return $all;
    }

    public function register($data)
    {
        return Project::create($data);
    }

    public function destroyByIntegrationSettingId($integration_setting_id)
    {
        $projects = Project::whereIntegrationSettingId($integration_setting_id)->get();
        foreach ($projects as $project) {
            $project->delete();
        }
    }
}
