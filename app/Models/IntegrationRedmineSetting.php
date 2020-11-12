<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationRedmineSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'integration_setting_id', 'url', 'api_key',
    ];

    public function integrationSetting()
    {
        return $this->belongsTo('App\Models\IntegrationSetting');
    }
}
