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
use Illuminate\Support\Collection;

class EnergyController extends Controller
{
    private MtecConnector $mtec;

    private KostalConnector $kostal;

    private Smart1XMLRPCClient $smart1;

    private Collection $smart1counters;

    public function __construct()
    {
        $this->mtec = new MtecConnector();
        $this->kostal = new KostalConnector();
        $this->smart1 = new Smart1XMLRPCClient(config('pv-heiz.base_url'));

        $this->smart1counters = collect($this->smart1->getCounters(config('pv-heiz.password')));
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
            'PowerProduced' => $this->smart1counters->get('pv_global_1446025417')['Today_Usage'],
            'kostal' => $kostalDataW,
            'mtec' => $mtecDataW,
        ]);
    }

    public function getBatteryData(): JsonResponse
    {
        $mtecValue = $this->mtec->send(new BatteryPower())->json('value');
        if (abs($mtecValue) > 50000) {
            $mtecValue = 0;
        }

        return response()->json([
            'soc' => $this->mtec->send(new Soc())->json('value'),
            'consumption_w' => $mtecValue * -1,
        ]);
    }

    public function getEvuData(): JsonResponse
    {
        $mtecValue = $this->mtec->send(new GridPower())->json('value') * -1;
        if (abs($mtecValue) > 50000) {
            $mtecValue = 0;
        }
        $quantum = $this->smart1counters->get('calculationcounter_1681462391')['Current_Value'] * -1;

        $totalPower = $mtecValue + $quantum;

        // negative werte = einspeisung
        // positive werte = bezug
        return response()->json([
            'power' => $totalPower,
            'energyOut' => $this->smart1counters->get('buscounter_1414675701')['Today_Usage'],
            'energyIn' => $this->smart1counters->get('buscounter_1414675598')['Today_Usage'],
            'mtec' => $mtecValue,
            'quantum' => $quantum
        ]);
    }
}
