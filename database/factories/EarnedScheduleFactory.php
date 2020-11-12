<?php

namespace Database\Factories;

use App\Models\EarnedSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EarnedScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EarnedSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'milestone_id' => '',
            'earned_schedule_date' => $this->faker->dateTimeBetween($startDate = '-30 day', $endDate = 'now', $timezone = null),
            'budget_at_completion' => $this->faker->numberBetween($min = 0, $max = 500),
            'planned_value' => $this->faker->numberBetween($min = 0, $max = 500),
            'actual_cost' => $this->faker->numberBetween($min = 0, $max = 500),
            'earned_value' => $this->faker->numberBetween($min = 0, $max = 500),
        ];
    }
}
