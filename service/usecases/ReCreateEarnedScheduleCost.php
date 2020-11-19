<?php

namespace Service\UseCases;

use Service\Repositories\RedmineIssueRepository;
use Service\Repositories\RedmineMilestoneRepository;
use Service\Repositories\RedmineTimeEntryRepository;
use Service\Repositories\MilestoneRepository;
use Service\Repositories\IntegrationRedmineSettingRepository;
use Service\Repositories\EarnedScheduleRepository;
use Carbon\Carbon;

class ReCreateEarnedScheduleCost
{
    private $redmine_issueRepository;
    private $redmine_milestoneRepository;
    private $redmine_timeEntryRepository;
    private $projectRepository;
    private $milestone;
    private $milestoneRepository;

    public function __construct($milestone_id) {
        $this->milestoneRepository = new MilestoneRepository();
        $this->earnedScheduleRepository = new EarnedScheduleRepository();
        $this->redmine_milestoneRepository = new RedmineMilestoneRepository();
        $this->milestone = $this->milestoneRepository->get($milestone_id);
        $settingRepository = new IntegrationRedmineSettingRepository();
        $setting = $settingRepository->getByIntegrationSettingId($this->milestone->project->integrationSetting->id);
        $this->redmine_issueRepository = new RedmineIssueRepository($setting->url, $setting->api_key);
        $this->redmine_timeEntryRepository = new RedmineTimeEntryRepository($setting->url, $setting->api_key);
    }

    public function execute()
    {
        $version_id = $this->redmine_milestoneRepository->getByMilestoneId($this->milestone->id)->original_version_id;
        $issues = $this->redmine_issueRepository->getByVersionIdInJournals($version_id);
        $time_entries = [];
        foreach ($issues as $key => $issue) {
            $time_entries_in_issue = $this->redmine_timeEntryRepository->getByIssueId($issue['id']);
            $time_entries = array_merge_recursive($time_entries, $time_entries_in_issue);
        }

        $this->earnedScheduleRepository->destroyByMilestoneId($this->milestone->id);

        $min_date = new Carbon(min(array_merge(array_column($time_entries, 'spent_on'), array_column($issues, 'start_date'))), 'Asia/Tokyo');
        $max_date = new Carbon(max(array_merge(array_column($time_entries, 'spent_on'), array_column($issues, 'closed_on'), array_column($issues, 'due_date'))), 'Asia/Tokyo');
        $max_date->hour = 0;
        $max_date->minute = 0;
        $max_date->second = 0;
        $bac = array_sum(array_column($issues, 'estimated_hours'));


        // 初期化(現時点の値は不要)
        foreach ($issues as $key => $issue) {
            $issues[$key]['done_ratio'] = 0;
            $issues[$key]['planned_value'] = 0;
            $issues[$key]['earned_value'] = 0;
        }

        // メインループ
        for ($current_date = $min_date->copy(); $current_date <= $max_date ; $current_date->addDay()) {
            $registration_data = [
                'milestone_id' => $this->milestone->id,
                'earned_schedule_date' => $current_date,
                'budget_at_completion' => $bac,
                'planned_value' => 0,
                'actual_cost' => 0,
                'earned_value' => 0
            ];

            foreach ($issues as $key => $issue) {
                $issue_start_date = new Carbon($issue['start_date'], 'Asia/Tokyo') ?? $min_date;
                $issue_end_date = new Carbon($issue['due_date'], 'Asia/Tokyo') ?? $max_date;

                // 対象日が開始日より前の場合はスキップ
                $planned_value = 0;
                if($current_date->gte($issue_start_date)){
                    $one_point = $issue['estimated_hours'] * (1 / ($issue_start_date->diffInDays($issue_end_date) + 1));
                    $diffDays = ($issue_start_date->diffInDays($current_date) + 1);
                    
                    $calced_estimate_hours = ceil($one_point * $diffDays * 100)/100;
                    $planned_value = $current_date->gte($issue_end_date) ? $issue['estimated_hours'] : $calced_estimate_hours;
                }

                $issues[$key]['planned_value'] = $planned_value;
                foreach ($issue['journals'] as $key2 => $journal) {
                    $created_on = new Carbon($journal['created_on'], 'Asia/Tokyo');
                    $created_on->hour = 0;
                    $created_on->minute = 0;
                    $created_on->second = 0;
                    foreach ($journal['details'] as $key3 => $journal_detail) {
                        if($journal_detail['name'] == 'done_ratio'){
                            if($created_on->ne($current_date)) continue;
                            $issues[$key]['done_ratio'] = $journal_detail['new_value'];
                        }
                    }
                }

                $issues[$key]['earned_value'] = ceil($issues[$key]['estimated_hours'] * ($issues[$key]['done_ratio'])) / 100;
            }
            foreach ($issues as $key => $issue) {
                // 見積工数が0もしくは空の場合はスキップ
                if(!isset($issue['estimated_hours'])) continue;
                if($issue['estimated_hours'] == 0) continue;

                $has_child = count(array_keys(array_column($issues, 'parent_id'), $issue['id'])) > 0;

                if($has_child){
                    $issues[$key]['done_ratio'] = $this->done_ratio($issues, $issue['id']);
                }

                $issues[$key]['earned_value'] = ceil($issues[$key]['estimated_hours'] * ($issues[$key]['done_ratio'])) / 100;
            }
            $actual_cost = 0;
            foreach ($time_entries as $key => $time_entry) {
                $spent_on = new Carbon($time_entry['spent_on'], 'Asia/Tokyo');
                
                if($current_date->gte($spent_on)){
                    $actual_cost += $time_entry['hours'];
                }
            }

            $registration_data['planned_value'] = array_sum(array_column($issues, 'planned_value'));
            $registration_data['earned_value'] = array_sum(array_column($issues, 'earned_value'));
            $registration_data['actual_cost'] = $actual_cost;

            $this->earnedScheduleRepository->register($registration_data);
        }
        return;
    }

    private function done_ratio($childs, $issue_id){
        $done_ratio = 0;

        $zero_estimateds = [];
        $in_estimateds = [];

        foreach ($childs as $key => $child) {
            if(!isset($child['parent_id'])) continue ;
            if($child['parent_id'] == $issue_id){
                $estimated_hours = $child['estimated_hours'] ?? 0;
                $earned_value = 0;
                $done_ratio = 0;
                $has_child = count(array_keys(array_column($childs, 'parent_id'), $child['id'])) > 0;

                if($has_child){
                    $done_ratio = $this->done_ratio($childs, $child['id']);
                    $earned_value = $estimated_hours * ($done_ratio / 100);
                }else{
                    $done_ratio = $child['done_ratio'];
                    $earned_value = $child['earned_value'];
                }

                $row = [];
                $row['issue_id'] = $child['id'];
                $row['done_ratio'] = $done_ratio;
                $row['estimated_hours'] = $estimated_hours;
                $row['earned_value'] = $earned_value;

                if($estimated_hours > 0){
                    $in_estimateds[] = $row;
                }else{
                    $zero_estimateds[] = $row;
                }
            }
        }

        $sum_in_estimated_hour = array_sum(array_column($in_estimateds, 'estimated_hours'));
        $default_estimated_hour = 0;

        $default_estimated_hour = $sum_in_estimated_hour > 0 ? ceil($sum_in_estimated_hour / count($in_estimateds) * 100) / 100 : 100;

        foreach ($zero_estimateds as $key => $zero_estimated) {
            $zero_estimateds[$key]['estimated_hours'] = $default_estimated_hour;
            $zero_estimateds[$key]['earned_value'] = ($zero_estimateds[$key]['estimated_hours'] * (($zero_estimated['done_ratio']) / 100));
        }

        $estimateds = array_merge_recursive($in_estimateds, $zero_estimateds);

        $sum_estimated_hours = array_sum(array_column($estimateds, 'estimated_hours'));
        $sum_earned_value = array_sum(array_column($estimateds, 'earned_value'));

        $done_ratio = $sum_earned_value != 0 ? ceil($sum_earned_value / $sum_estimated_hours * 100) : 0;

        return $done_ratio;
    }
}
