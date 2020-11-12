<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnedSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'earned_schedule_date',
        'budget_at_completion',
        'planned_value',
        'actual_cost',
        'earned_value'
    ];

    protected $appends = [
        'cost_variance',
        'schedule_variance',
        'cost_performance_index',
        'schedule_performance_index',
        'estimate_at_completion',
        'estimate_to_completion',
        'variance_at_completion',
    ];

    public function getCostVarianceAttribute($value)
    {
        return $this->earned_value - $this->actual_cost;
    }

    public function getScheduleVarianceAttribute($value)
    {
        return $this->earned_value - $this->planned_value;
    }

    public function getCostPerformanceIndexAttribute($value)
    {
        return $this->actual_cost != 0 ? $this->earned_value / $this->actual_cost : 0;
    }

    public function getSchedulePerformanceIndexAttribute($value)
    {
        return $this->planned_value != 0 ? $this->earned_value / $this->planned_value : 0;
    }

    public function getEstimateAtCompletionAttribute($value)
    {
        return $this->cost_performance_index != 0 ? $this->actual_cost + ($this->budget_at_completion - $this->earned_value) / $this->cost_performance_index : 0;
    }

    public function getEstimateToCompletionAttribute($value)
    {
        return $this->cost_performance_index != 0 ? ($this->budget_at_completion - $this->earned_value) / $this->cost_performance_index : 0;
    }

    public function getVarianceAtCompletionAttribute($value)
    {
        return $this->cost_performance_index != 0 ? $this->budget_at_completion - $this->estimate_at_completion : 0;
    }
}
