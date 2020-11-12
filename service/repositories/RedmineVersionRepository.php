<?php

namespace Service\Repositories;

class RedmineVersionRepository extends RedmineBaseRepository
{
    function __construct($url, $api_key) {
        parent::__construct($url, $api_key);
    }

    public function getByProjectId($project_id)
    {
        $offset = 0;
        $limit = 100;
        $response = $this->client->version->all($project_id, ['limit' => 1, 'offset' => $offset]);

        if($response == null) return;

        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $result = array_merge_recursive($result, $this->client->version->all($project_id, ['limit' => $limit, 'offset' => $i])["versions"]);
        }

        //add column parent project_id
        $result = self::filterArrayByKeys($result,['id', 'name']);
        foreach ($result as $key => $version) {
            $result[$key]["project_id"] = $project_id;
        }
        return $result;
    }
}
