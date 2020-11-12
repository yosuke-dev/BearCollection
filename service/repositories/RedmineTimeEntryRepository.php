<?php

namespace Service\Repositories;

class RedmineTimeEntryRepository extends RedmineBaseRepository
{
    function __construct($url, $api_key) {
        parent::__construct($url, $api_key);
    }

    public function getByIssueId($issue_id)
    {
        $offset = 0;
        $limit = 100;
        $params = ['limit' => 1, 'offset' => $offset, 'issue_id' => $issue_id];
        $response = $this->client->time_entry->all($params);

        if($response == null) return;

        $params['limit'] = $limit;

        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $params['offset'] = $i;
            $result = array_merge_recursive($result, $this->client->time_entry->all($params)['time_entries']);
        }
        $time_entries = self::filterArrayByKeys($result, ['spent_on', 'hours']);

        foreach ($time_entries as $key => $value) {
            $time_entries[$key]['issue_id'] = $issue_id;
        }

        return $time_entries;
    }
}
