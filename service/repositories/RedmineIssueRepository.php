<?php

namespace Service\Repositories;

class RedmineIssueRepository extends RedmineBaseRepository
{
    function __construct($url, $api_key) {
        parent::__construct($url, $api_key);
    }

    public function getByVersionId($version_id)
    {
        $offset = 0;
        $limit = 100;
        $params = ['limit' => 1, 'offset' => $offset, 'fixed_version_id' => $version_id, 'status_id' => '*'];
        $response = $this->client->issue->all($params);

        if($response == null) return;

        $params['limit'] = $limit;

        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $params['offset'] = $i;
            $result = array_merge_recursive($result, $this->client->issue->all($params)['issues']);
        }

        $issues = $result;

        $journal_params = ['include' => 'journals'];
        $result = [];
        foreach ($issues as $key => $issue) {
            $issue_in_journals = $this->client->issue->show($issue['id'], $journal_params);
            $result['issues'][] = $issue_in_journals['issue'];
        }

        $issues = self::filterArrayByKeys($result['issues'], ['id', 'parent', 'start_date', 'due_date', 'closed_on', 'estimated_hours', 'journals']);

        $issues = [];

        foreach ($result['issues'] as $key => $issue) {
            $add_row = [];
            $add_row['id'] = $issue['id'];
            $add_row['parent_id'] = isset($issue['parent']) ? $issue['parent']['id'] : null;
            $add_row['start_date'] = $issue['start_date'];
            $add_row['due_date'] = $issue['due_date'];
            $add_row['closed_on'] = $issue['closed_on'];
            $add_row['estimated_hours'] = $issue['estimated_hours'];
            $add_row['journals'] = isset($issue['journals']) ? array_reverse($issue['journals']) : null;

            $issues[] = $add_row;
        }

        return $issues;
    }
}
