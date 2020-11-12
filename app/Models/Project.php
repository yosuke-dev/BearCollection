<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'integration_setting_id', 'identifier'
    ];

    public function milestones()
    {
        return $this->hasMany('App\Models\Milestone');
    }

    public function integrationSetting()
    {
        return $this->belongsTo('App\Models\IntegrationSetting');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($project) {
            $project->milestones()->delete();
        });
    }
}
