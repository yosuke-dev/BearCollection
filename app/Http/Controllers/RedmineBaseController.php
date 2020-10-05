<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redmine\Client;

class RedmineBaseController extends Controller
{
    protected $client;

    public function __construct() {
        $this->client = new Client(config('app.redmine_url'), config('app.redmine_api'));
    }

    public function filterArrayByKeys(array $input, array $column_keys){
        $encodedInput = json_decode(json_encode($input, JSON_UNESCAPED_UNICODE), true);
        $result = array();
        $column_keys = array_flip($column_keys); // getting keys as values
        foreach ($encodedInput as $key => $val) {
            // getting only those key value pairs, which matches $column_keys
            $result[$key] = array_intersect_key($val, $column_keys);
        }
        return $result;
    }
}
