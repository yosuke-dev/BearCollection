<?php

namespace Service\UseCases;

use Service\Repositories\RedmineProjectRepository;
use Service\Repositories\RedmineVersionRepository;
use Service\Repositories\RedmineMilestoneRepository;
use Service\Repositories\ProjectRepository;
use Service\Repositories\MilestoneRepository;

class RegisterProjectVersionRedmine
{
    private $redmine_projectRepository;
    private $redmine_versionRepository;
    private $projectRepository;
    private $milestoneRepository;

    public function __construct($url, $api_key) {
        $this->redmine_projectRepository = new RedmineProjectRepository($url, $api_key);
        $this->redmine_versionRepository = new RedmineVersionRepository($url, $api_key);
        $this->redmine_milestoneRepository = new RedmineMilestoneRepository();
        $this->projectRepository = new ProjectRepository();
        $this->milestoneRepository = new MilestoneRepository();
    }

    public function execute($integration_setting_id)
    {
        $this->projectRepository->destroyByIntegrationSettingId($integration_setting_id);

        $projects = $this->redmine_projectRepository->all();

        foreach ($projects as $key => $project) {
            $project["integration_setting_id"] = $integration_setting_id;

            $versions = $this->redmine_versionRepository->getByProjectId($project["id"]);

            if($versions == null) continue;

            $project = $this->projectRepository->register($project);
            foreach ($versions as $key => $version) {
                $version["project_id"] = $project->id;
                $milestone = $this->milestoneRepository->register($version);
                $this->redmine_milestoneRepository->register(['milestone_id' => $milestone->id, 'original_version_id' => $version["id"]]);
            }
        }
    }
}
