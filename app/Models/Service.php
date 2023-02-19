<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $casts = [
        'config' => 'array',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
