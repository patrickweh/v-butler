<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Integrations\Wibutler\Requests\Devices;
use App\Http\Integrations\Wibutler\Requests\Devices\Components\Patch;
use App\Http\Integrations\Wibutler\WibutlerConnector;
use App\Models\Device;
use App\Models\Service;

class WibutlerController extends Controller
{
    public WibutlerConnector $connector;

    public function __construct()
    {
        $this->connector = new WibutlerConnector();
    }

    public function on(Device $device)
    {
        $this->toggleState($device, true);
    }

    public function off(Device $device)
    {
        $this->toggleState($device, false);
    }

    private function toggleState(Device $device, bool $on)
    {
        $component = match ($device->component) {
            'blind' => 'SWT_POS',
            'switch' => 'SWT'
        };

        $payload = [
            'type' => 'switch',
            'value' => $on ? 'ON' : 'OFF',
        ];

        $this->connector->send(new Patch($device, $component, $payload));
    }

    public function value(Device $device, string $value)
    {
        $component = 'POS';
        $payload = [
            'type' => 'numeric',
            'value' => $value,
        ];

        $this->connector->send(new Patch($device, $component, $payload));
    }

    public function import()
    {
        $service = Service::query()->where('name', 'wibutler')->first();
        $response = $this->connector->send(new Devices());

        foreach ($response->json('devices') ?? [] as $wibutlerDevice) {
            $component = match ($wibutlerDevice['type'] ?? false) {
                'Blind' => 'blind',
                'SwitchingRelays' => 'switch',
                'WeatherSensors' => 'weather',
                'FloorHeatingController' => 'heating',
                'RoomOperatingPanels' => 'room-temperature',
                'Switches' => 'double-switch',
                default => 'none'
            };

            $components = collect($wibutlerDevice['components']);

            $device = Device::withTrashed()
                ->where('foreign_id', $wibutlerDevice['id'])
                ->where('service_id', $service->id)
                ->firstOrNew();

            $value = match ($wibutlerDevice['type']) {
                'Blind' => $components->where('name', 'CURPOS')->first()['value'] ?? null,
                'FloorHeatingController' => $components->where('name', 'TSP')->first()['value'] ?? null,
                'RoomOperatingPanels' => (float) $wibutlerDevice['statetext'],
                default => null
            };

            $device->fill([
                'name' => $device->exists ? $device->name : $wibutlerDevice['name'],
                'component' => $component,
                'details' => $wibutlerDevice,
                'is_on' => (bool) ($components->where('name', 'STATE')->first()['value'] ?? null),
                'value' => $value,
                'service_id' => $service->id,
                'foreign_id' => $wibutlerDevice['id'],
            ]);
            $device->save();
        }
    }
}
