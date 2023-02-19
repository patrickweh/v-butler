<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NukiController extends Controller
{
    public function on(Device $device)
    {
        // Action 3 = unlatch
        $this->sendRequest($device->service, 'lockAction', ['action' => 3], $device);
    }

    public function off(Device $device)
    {
        // Action 2 = lock
        $this->sendRequest($device->service, 'lockAction', ['action' => 2], $device);
    }

    public function import(Service $service)
    {
        $devices = $this->sendRequest($service, '/list');
        foreach ($devices as $nukiDevice) {
            $device = Device::query()
                ->where('foreign_id', $nukiDevice->nukiId)
                ->where('service_id', $service->id)
                ->firstOrNew();

            $device->fill([
                'name' => $device->exists ? $device->name : $nukiDevice->name,
                'component' => 'lock',
                'details' => $nukiDevice,
                'is_on' => $nukiDevice->lastKnownState->doorsensorState === 3,
                'value' => $nukiDevice->lastKnownState->batteryChargeState,
                'foreign_id' => $nukiDevice->nukiId,
                'service_id' => $service->id,
            ]);
            $device->save();
        }
    }

    public function trigger(Request $request)
    {
        $data = $request->all();
        $device = Device::query()->where('foreign_id', $data['nukiId'])->firstOrFail();

        $device->value = $data['batteryChargeState'] ?? 0;
        $device->is_on = ($data['doorsensorState'] ?? false) === 3;
        $device->save();
    }

    private function sendRequest(Service $service, string $slug, array $params = [], Model $device = null)
    {
        $params['token'] = $service->token;
        $params['nukiId'] = $device?->foreign_id;
        $params['nowait'] = 1;

        $url = rtrim($service->url, '/').'/'.ltrim($slug, '/').'?'.http_build_query($params);
        $response = Http::get($url);

        return json_decode($response->getBody()->getContents());
    }
}
