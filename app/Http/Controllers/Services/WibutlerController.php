<?php

namespace App\Http\Controllers\Services;

use App\Helpers\WibutlerClient;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Service;

class WibutlerController extends Controller
{
    public function on(Device $device)
    {
        $client = new WibutlerClient($device->service);
        $this->toggleState($device, $client, true);
    }

    public function off(Device $device)
    {
        $client = new WibutlerClient($device->service);
        $this->toggleState($device, $client, false);
    }

    private function toggleState(Device $device, WibutlerClient $client, bool $on)
    {
        $component = match ($device->component) {
            'blind' => 'SWT_POS',
            'switch' => 'SWT'
        };

        $payload = [
            'type' => 'switch',
            'value' => $on ? 'ON' : 'OFF',
        ];

        $client->sendCommand(
            slug: 'devices/'.$device->foreign_id.'/components/'.$component,
            method: 'PATCH',
            body: $payload
        );
    }

    public function value(Device $device, string $value)
    {
        $client = new WibutlerClient($device->service);

        $component = 'POS';
        $payload = [
            'type' => 'numeric',
            'value' => $value,
        ];

        $client->sendCommand(
            slug: 'devices/'.$device->foreign_id.'/components/'.$component,
            method: 'PATCH',
            body: $payload
        );
    }

    public function import(Service $service)
    {
        $client = new WibutlerClient($service);
        $response = $client->sendCommand('devices');

        foreach ($response->devices as $wibutlerDevice) {
            $component = match ($wibutlerDevice->type) {
                'Blind' => 'blind',
                'SwitchingRelays' => 'switch',
                'WeatherSensors' => 'weather',
                'FloorHeatingController' => 'heating',
                'RoomOperatingPanels' => 'room-temperature',
                default => 'none'
            };

            $components = collect($wibutlerDevice->components);

            $device = Device::query()
                ->where('foreign_id', $wibutlerDevice->id)
                ->where('service_id', $service->id)
                ->firstOrNew();

            $value = match ($wibutlerDevice->type) {
                'Blind' => $components->where('name', 'CURPOS')->first()?->value ?? null,
                'FloorHeatingController' => $components->where('name', 'TSP')->first()?->value ?? null,
                'RoomOperatingPanels' => (float) $wibutlerDevice->statetext,
                default => null
            };

            $device->fill([
                'name' => $device->exists ? $device->name : $wibutlerDevice->name,
                'component' => $component,
                'details' => $wibutlerDevice,
                'is_on' => (bool) $components->where('name', 'STATE')->first()?->value ?? null,
                'value' => $value,
                'service_id' => $service->id,
                'foreign_id' => $wibutlerDevice->id,
            ]);
            $device->save();
        }
    }
}
