<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Service;
use SoapClient;
use SoapParam;

class PresenceController extends Controller
{
    public function import(Service $service)
    {
        $devices = Device::query()->where('service_id', $service->id)->get();
        $activeHosts = collect($this->getAllHosts($service));

        foreach ($devices as $device) {
            $host = $activeHosts->where('NewMACAddress', $device->foreign_id)->first();
            $device->is_on = $host['NewActive'] ?? false;
            $device->save();
        }
    }

    private function getAllHosts(Service $service = null): array
    {
        $url = $service->url ?? 'fritz.box';
        $client = new SoapClient(null, ['location' => 'http://'.$url.':49000/upnp/control/hosts',
            'uri' => 'urn:dslforum-org:service:Hosts:1',
            'soapaction' => 'urn:dslforum-org:service:Hosts:1#GetSpecificHostEntry',
            'noroot' => false,
        ]);

        $totalHosts = $client->GetHostNumberOfEntries();

        $hosts = [];
        if (! (is_soap_fault($totalHosts))) {
            for ($i = 0; $i < $totalHosts; $i++) {
                $hosts[] = $client->GetGenericHostEntry(new SoapParam($i, 'NewIndex'));
            }
        }

        return $hosts;
    }
}
