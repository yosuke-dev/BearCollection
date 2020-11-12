<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'integration_service_id',
    ];

    public function integrationService()
    {
        return $this->belongsTo('App\Models\IntegrationService');
    }

    public function integrationRedmineSetting()
    {
        return $this->hasOne('App\Models\IntegrationRedmineSetting');
    }

    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($integration_setting) {
            foreach ($integration_setting->projects()->get() as $project) {
                $project->delete();
            };
            $integration_setting->integrationRedmineSetting()->delete();
        });
    }
}
