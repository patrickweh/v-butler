<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Room extends Model
{
    use HasFactory, Searchable;

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'room_device');
    }
}
