<?php

namespace Service\Repositories;
use Redmine\Client;

class RedmineBaseRepository extends BaseRepository
{
    protected $client;

    public function __construct($url, $api_key) {
        $this->client = new Client($url, $api_key);
    }
}
