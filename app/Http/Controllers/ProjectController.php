<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Service\UseCases\GetRedmineProjectVersions;

class ProjectController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $input = new GetRedmineProjectVersions;
        $data = $input->execute();
        return Inertia::render('Projects/Index', ['projects' => $data["projects"], 'versions' => $data["versions"]]);
    }
}
