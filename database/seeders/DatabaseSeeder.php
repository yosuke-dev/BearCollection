<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntegrationService;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\EarnedSchedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        IntegrationService::factory()->create([
            'name' => 'Backlog',
        ]);

        IntegrationService::factory()->create([
            'name' => 'Jira',
        ]);

        IntegrationService::factory()->create([
            'name' => 'Redmine',
        ]);
    }
}
