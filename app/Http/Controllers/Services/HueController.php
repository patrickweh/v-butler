<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Service;
use Illuminate\Http\Request;
use Phue\Client;
use Phue\Command\SetLightState;

class HueController extends Controller
{
    /**
     * @param Device $device
     *
     * @return void
     */
    public function on(Device $device): void
    {
        $client = new Client($device->service->url, $device->service->token);
        $this->toggleState($device, $client, true);
    }

    /**
     * @param Device $device
     *
     * @return void
     */
    public function off(Device $device): void
    {
        $client = new Client($device->service->url, $device->service->token);
        $this->toggleState($device, $client, false);
    }

    /**
     * @param Device $device
     * @param int    $value
     *
     * @return void
     */
    public function value(Device $device, int $value): void
    {
        $value = $value > 1 ? round($value * 2.55) : $value;

        $client = new Client($device->service->url, $device->service->token);
        $params = new SetLightState($device->foreign_id);
        $params->on()->brightness($value);
        $client->sendCommand($params);

        $devices->is_on = true;
        $devices->value = $value;
        $device->save();
    }

    /**
     * @param Device $device
     * @param Client $client
     * @param bool   $on
     *
     * @return void
     */
    private function toggleState(Device $device, Client $client, bool $on): void
    {
        $params = new SetLightState($device->foreign_id);
        $params->on($on)->brightness(254);

        $client->sendCommand($params);
        $device->is_on = $on;
        $device->value = 254;
        $device->save();
    }

    /**
     * @param Service $service
     *
     * @return void
     */
    public function import(Service $service): void
    {
        $client = new Client($service->url, $service->token);
        $lights = $client->getLights();

        foreach ($lights as $light) {
            $device = Device::query()
                ->where('foreign_id', $light->getId())
                ->where('service_id', $service->id)
                ->firstOrNew();

            $device->fill([
                'name' => $device->exists ? $device->name : $light->getName(),
                'component' => 'light',
                'details' => $light,
                'is_on' => $light->isOn(),
                'value' => $light->getBrightness(),
                'service_id' => $service->id,
                'foreign_id' => $light->getId(),
            ]);
            $device->save();
        }
    }
}
