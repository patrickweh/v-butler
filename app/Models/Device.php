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
    use Searchable, SoftDeletes;

    protected $appends = [
        'is_favorite',
        'room_ids',
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
        'deleted_by',
    ];

    protected $dispatchesEvents = [
        'created' => DeviceCreated::class,
        'updated' => DeviceUpdated::class,
    ];

    public array $meilisearchSettings = [
        'updateFilterableAttributes' => [
            'room_ids',
        ],
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function children()
    {
        return $this->belongsToMany(Device::class, 'device_devices', 'parent_id');
    }

    public function allDescendants()
    {
        $descendants = collect();

        foreach ($this->children()->with('service')->get() as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->allDescendants());
        }

        return $descendants;
    }

    public function parent()
    {
        return $this->belongsToMany(Device::class, 'device_devices', 'device_id', 'parent_id');
    }

    public function isFavorite(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (bool) $this->users()->whereKey(Auth::id())->first(),
            set: fn ($value) => $value ?
                Auth::user()->devices()->attach($this->id) :
                Auth::user()->devices()->detach($this->id)
        );
    }

    protected function roomIds(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->rooms->pluck('id')->toArray(),
            set: fn ($value) => $this->rooms()->sync($value)
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
