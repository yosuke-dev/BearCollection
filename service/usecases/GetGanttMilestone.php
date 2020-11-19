<?php

namespace Service\UseCases;

use Service\Repositories\EarnedScheduleRepository;
use Service\Repositories\MilestoneRepository;
use Service\Repositories\RedmineMilestoneRepository;
use Service\Repositories\IntegrationRedmineSettingRepository;
use Service\Repositories\RedmineIssueRepository;
use Carbon\Carbon;

class GetGanttMilestone
{
    private $redmine_issueRepository;
    private $redmine_milestoneRepository;
    private $milestone;
    private $milestoneRepository;

    public function __construct($milestone_id) {
        $this->milestoneRepository = new MilestoneRepository();
        $this->redmine_milestoneRepository = new RedmineMilestoneRepository();
        $this->milestone = $this->milestoneRepository->get($milestone_id);
        $settingRepository = new IntegrationRedmineSettingRepository();
        $setting = $settingRepository->getByIntegrationSettingId($this->milestone->project->integrationSetting->id);
        $this->redmine_issueRepository = new RedmineIssueRepository($setting->url, $setting->api_key);
    }

    public function execute()
    {
        $version_id = $this->redmine_milestoneRepository->getByMilestoneId($this->milestone->id)->original_version_id;
        $result = $this->redmine_issueRepository->getByVersionId($version_id);

        $min_date = new Carbon(min(array_merge(array_column($result, 'start_date'))), 'Asia/Tokyo');
        $max_date = new Carbon(max(array_merge(array_column($result, 'closed_on'), array_column($result, 'due_date'))), 'Asia/Tokyo');
        $max_date->hour = 0;
        $max_date->minute = 0;
        $max_date->second = 0;

        $issues = [];

        foreach ($result as $key => $issue) {
            $add_row = [];

            $start_date = new Carbon($issue['start_date'], 'Asia/Tokyo');
            $due_date = new Carbon($issue['due_date'] ?? $issue['closed_on'] ?? $max_date, 'Asia/Tokyo');

            $style_task = array('base' => ['fill' => '#667eea', 'stroke' => '#2b6cb0']);
            $style_milestone = array('base' => ['fill' => '#38b2ac', 'stroke' => '#2c7a7b']);
            $style_done = array('base' => ['fill' => '#a0aec0', 'stroke' => '#4a5568']);

            $is_done = isset($issue['closed_on']);
            $type = isset($issue['parent_id']) ? 'task' : 'project';

            $add_row['id'] = $issue['id'];
            $add_row['parentId'] = $issue['parent_id'];
            $add_row['label'] = $issue['subject'];
            $add_row['status'] = $issue['status'];
            $add_row['tracker'] = $issue['tracker'];
            $add_row['type'] = $type;
            $add_row['user'] = $issue['assigned_to'];
            $add_row['start'] = $start_date->format('Y/m/d');
            $add_row['due_date'] = $due_date->format('Y/m/d');
            $add_row['duration'] = ($start_date->diffInSeconds($due_date) + 1) * 1000;
            $add_row['progress'] = $is_done ? 100 : $issue['done_ratio'];
            $add_row['style'] = $is_done ? $style_done : ($type == 'task' ? $style_task : $style_milestone);

            $issues[] = $add_row;
        }

        return $issues;
    }
}
