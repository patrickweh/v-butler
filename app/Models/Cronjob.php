<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cronjob extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'command_params' => 'array',
        'schedule_params' => 'array',
    ];
}
