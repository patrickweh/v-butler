<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\KostalService;
use App\Http\Controllers\Services\MtecService;
use Illuminate\Http\JsonResponse;

class EnergyController extends Controller
{
    private MtecService $mtecService;

    public function __construct()
    {
        $this->mtecService = new MtecService();
    }

    public function getPvData(): JsonResponse
    {
        $kostalService = new KostalService();

        $mtecDataTodayW = $this->mtecService->getGridConnectedData(config('mtec.station_id'))->get('eRatioGraph')['eDayTotal'] * 1000;
        $kostalDataTodayW = $kostalService->getInverterData('scb:statistic:EnergyFlow', [
            'Statistic:Yield:Day',
        ])->get('Statistic:Yield:Day')['value'];

        $kostalDataW = $kostalService->getInverterData('devices:local', [
            'Dc_P',
        ])->get('Dc_P')['value'];

        $mtecData = $this->mtecService->getSingleStationOverview(config('mtec.station_id'));
        $mtecDataW = $mtecData->pacUnit == 'kW' ? $mtecData->pac * 1000 : $mtecData->pac;

        return response()->json([
            'PowerOut' => round($kostalDataW + $mtecDataW),
            'PowerProduced' => round($mtecDataTodayW + $kostalDataTodayW),
            'mtec' => $kostalDataW,
            'kostal' => $mtecDataW,
        ]);
    }

    public function getBatteryData(): JsonResponse
    {
        $mtecData = $this->mtecService->getSingleStationOverview(config('mtec.station_id'));

        $watt = $mtecData->batteryPUnit == 'kW' ? 1000 : 1;
        $mtecData->arrowBatteryInverter ? $mtecData->batteryP = $mtecData->batteryP * -1 : $mtecData->batteryP = $mtecData->batteryP;

        return response()->json(['soc' => $mtecData->soc, 'consumption_w' => $mtecData->batteryP * $watt, 'unit' => $mtecData->batteryPUnit]);
    }

    public function getEvuData(): JsonResponse
    {
        $mtecData = $this->mtecService->getSingleStationOverview(config('mtec.station_id'));
        $multiplierPmeter = $mtecData->pmeterTotalUnit == 'kW' ? -1000 : -1;
        $multiplierPload = $mtecData->ploadUnit == 'kW' ? 1000 : 1;
        if ($mtecData->arrowGridInverter) {
            $mtecData->pmeterTotal = $mtecData->pmeterTotal * -1;
        }

        return response()->json(['power' => $mtecData->pmeterTotal * $multiplierPmeter, 'watt' => $mtecData->pload * $multiplierPload]);
    }
}
