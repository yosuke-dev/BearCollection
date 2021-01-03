<?php

namespace Service\Repositories;

use App\Models\Dashboard;

class DashboardRepository extends BaseRepository
{
    public function __construct()
    {
    }

    public function all()
    {
        $all = Dashboard::all();
        return $all;
    }

    public function register($data)
    {
        return Dashboard::create($data);
    }

    public function update($data)
    {
    }
}
