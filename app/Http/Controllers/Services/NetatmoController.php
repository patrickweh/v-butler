<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Netatmo\Clients\NAWSApiClient;

class NetatmoController extends Controller
{
    //

    public function import(Service $service)
    {
        // TODO: freakin netatmo gives me too_many_connections
        $config = $service->config;
        $client = new NAWSApiClient($config);

        if (! $service->token) {
            $client->setVariable('username', $service->user);
            $client->setVariable('password', $service->password);
            $tokens = $client->getAccessToken();

            $service->token = $tokens['refresh_token'];
        }

        $config['scope'] = 'read_station read_thermostat write_thermostat read_homecoach';
        $data = $client->getData();
        dd($data);
//
//        foreach($data['devices'][0]['modules'] as $device)
//        {
//            $device = (object)$device;
//            switch ($device->data_type[0]){
//                case 'Wind':
//                    $deviceType = DeviceType::query()->where('name','wind')->first();
//                    $unit = 'km/h';
//                    $value = $device->dashboard_data['WindStrength'];
//                    break;
//                case 'Rain':
//                    $deviceType = DeviceType::query()->where('name','rain')->first();
//                    $unit = 'mm';
//                    $value = $device->dashboard_data['Rain'];
//                    break;
//                default:
//                    $deviceType = DeviceType::query()->where('name','currentweather')->first();
//                    $value = null;
//                    $unit = null;
//            }
//
//            $model = Device::query()->where('externalDeviceId',$device->_id)->firstOrNew();
//            $model->name = $device->module_name;
//            $model->externalDeviceId = $device->_id;
//            $model->service_id = $this->service->id;
//            $model->deviceType_id = $deviceType->id;
//            $model->externalJson = $device;
//            $model->stateJson = array(
//                'Unit' => $unit,
//                'Name' => $device->module_name,
//                'Value' => $value
//            );
//            $model->save();
    }
}
