<?php

namespace Service\Repositories;

class RedmineProjectRepository extends RedmineBaseRepository
{
    function __construct($url, $api_key) {
        parent::__construct($url, $api_key);
    }

    public function all()
    {
        $offset = 0;
        $limit = 100;
        $response = $this->client->project->all(['limit' => 1, 'offset' => $offset]);

        if($response == null) return;

        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $result = array_merge_recursive($result, $this->client->project->all(['limit' => $limit, 'offset' => $i])["projects"]);
        }
        return self::filterArrayByKeys($result,['id', 'name', 'identifier']);
    }
}
