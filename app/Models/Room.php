<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Room extends Model
{
    use Searchable;

    protected $with = ['level'];

    protected $fillable = [
        'name',
        'level_id',
    ];

    public array $meilisearchSettings = [
        'updateFilterableAttributes' => [
            'level_id',
        ],
    ];

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'room_device');
    }

    public function groupDevices(): BelongsToMany
    {
        return $this->devices()->where('is_group', true);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
