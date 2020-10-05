<?php

namespace Service\Repositories;

class RedmineVersionRepository extends RedmineBaseRepository
{
    function __construct() {
        parent::__construct();
    }

    public function getByProjectId($project_id)
    {
        $offset = 0;
        $limit = 100;
        $response = $this->client->version->all($project_id, ['limit' => 1, 'offset' => $offset]);
        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $result = array_merge($result, $this->client->version->all($project_id, ['limit' => $limit, 'offset' => $i])["versions"]);
        }
        return self::filterArrayByKeys($result,['id', 'name']);
    }
}
