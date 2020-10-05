<?php

namespace Service\UseCases;

use Service\Repositories\RedmineProjectRepository;
use Service\Repositories\RedmineVersionRepository;

class GetRedmineProjectVersions
{
    private $projectRepository;
    private $versionRepository;

    public function __construct() {
        $this->projectRepository = new RedmineProjectRepository;
        $this->versionRepository = new RedmineVersionRepository;
    }

    public function execute()
    {
        $projects = $this->projectRepository->all();
        $versions = array();
        // foreach ($projects as $project) {
        //     array_push($versions, $this->versionRepository->getByProjectId($project["id"]));
        // }
        return ['projects' => $projects, 'versions' => $versions];
    }
}
