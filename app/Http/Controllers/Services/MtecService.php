<?php

namespace App\Http\Controllers\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;

class MtecService
{
    protected string $token;

    protected string $baseUrl;

    public function __construct()
    {
        $this->token = config('mtec.token');
        $this->baseUrl = config('mtec.base_url');
    }

    public function getSingleStationOverview(string $stationId)
    {
        $data = data_get(
            Http::withHeaders([
                'Accept' => '*/*',
                'Accept-Language' => 'en-US',
                'Authorization' => $this->token,
            ])
            ->get($this->baseUrl.'/api/sys/curve/station/getSingleStationOverview',
                [
                    'id' => $stationId,
                ]
            )
            ->json(),
            'data');

        return new Fluent($data);
    }

    public function getGridConnectedData(string $stationId, Carbon $date = null)
    {
        $data = data_get(Http::withHeaders([
            'Accept' => '*/*',
            'Accept-Language' => 'en-US',
            'Authorization' => $this->token,
        ])
        ->get($this->baseUrl.'/api/sys/curve/station/getGridConnectedData',
            [
                'id' => $stationId,
                'durationType' => 1,
                'date' => $date ?? date('Y-m-d'),
                'stationType' => 0,
                'timeZoneOffset' => 60,
            ]
        )
        ->json(), 'data');

        return new Fluent($data);
    }
}
