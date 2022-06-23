<?php

namespace App\Models;

use App\Events\DeviceCreated;
use App\Events\DeviceUpdated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Device extends Model
{
    use SoftDeletes, Searchable;

    protected $appends = [
        'is_favorite'
    ];

    protected $hidden = [
        'deleted_at',
        'deleted_by',
        'details',
    ];

    protected $casts = [
        'config' => 'array',
        'details' => 'array',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected $dispatchesEvents = [
        'created' => DeviceCreated::class,
        'updated' => DeviceUpdated::class
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function children()
    {
        return $this->belongsToMany(Device::class, 'device_devices', 'parent_id');
    }

    public function isFavorite(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (bool)$this->users()->whereKey(Auth::id())->first(),
            set: fn($value) => $value ?
                Auth::user()->devices()->attach($this->id) :
                Auth::user()->devices()->detach($this->id)
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_device');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_device');
    }
}
