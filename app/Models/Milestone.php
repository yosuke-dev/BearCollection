<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'project_id'
    ];

    public function redmineMilestone()
    {
        return $this->hasOne('App\Models\RedmineMilestone');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($milestone) {
            $milestone->redmineMilestone()->delete();
        });
    }
}
