<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedmineVersionController extends RedmineBaseController
{
    function __construct() {
        parent::__construct();
    }

    public function all($project_id)
    {
        $offset = 0;
        $limit = 100;
        $response = $this->client->version->all($project_id, ['limit' => 1, 'offset' => $offset]);
        $result = [];
        for ($i = 0, $size = $response["total_count"]; $i < $size; $i += $limit) {
            $result = array_merge($result, $this->client->version->all($project_id, ['limit' => $limit, 'offset' => $i])["versions"]);
        }
        var_dump($result);
        $result = self::filterArrayByKeys($result,['id', 'name']);
        var_dump($result);
        return self::filterArrayByKeys($result,['id', 'name']);
    }
}
