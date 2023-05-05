<?php

namespace App\Http\Controllers;

use App\Helpers\Smart1XMLRPCClient;
use App\Http\Integrations\Esp32\MtecConnector;
use App\Http\Integrations\Esp32\Requests\Ac\Battery\Power as BatteryPower;
use App\Http\Integrations\Esp32\Requests\Ac\Battery\Soc;
use App\Http\Integrations\Esp32\Requests\Ac\Grid\Power as GridPower;
use App\Http\Integrations\Esp32\Requests\Ac\Pv\Power as PvPower;
use App\Http\Integrations\Kostal\KostalConnector;
use App\Http\Integrations\Kostal\Requests\ProcessData\Module\ProcessData;
use Illuminate\Http\JsonResponse;

class EnergyController extends Controller
{
    private MtecConnector $mtec;

    private KostalConnector $kostal;

    public function __construct()
    {
        $this->mtec = new MtecConnector();
        $this->kostal = new KostalConnector();
    }

    public function getPvData(): JsonResponse
    {
        $kostalDataW = $this->kostal
            ->send(new ProcessData('devices:local', 'Dc_P'))
            ->json('0.processdata.0.value');
        $kostalDataTodayW = $this->kostal
            ->send(new ProcessData('scb:statistic:EnergyFlow', 'Statistic:Yield:Day'))
            ->json('0.processdata.0.value');

        $mtecDataW = $this->mtec->send(new PvPower())->json('value');

        return response()->json([
            'PowerOut' => round($kostalDataW + $mtecDataW),
            'PowerProduced' => round(0 + $kostalDataTodayW),
            'kostal' => $kostalDataW,
            'mtec' => $mtecDataW,
        ]);
    }

    public function getBatteryData(): JsonResponse
    {
        return response()->json([
            'soc' => $this->mtec->send(new Soc())->json('value'),
            'consumption_w' => $this->mtec->send(new BatteryPower())->json('value') * -1,
        ]);
    }

    public function getEvuData(): JsonResponse
    {
        $mtecValue = $this->mtec->send(new GridPower())->json('value') * -1;
        $ctrl = new Smart1XMLRPCClient(config('pv-heiz.base_url'));
        $counters = collect($ctrl->getCounters(config('pv-heiz.password'))['Reply']);

        $mtecValue = $mtecValue + ($counters->get('calculationcounter_1681462391')->Current_Value * -1);

        // negative werte = einspeisung
        // positive werte = bezug
        return response()->json([
            'power' => $this->mtec->send(new GridPower())->json('value') * -1
        ]);
    }
}
