<?php

namespace App\Console\Commands;

use App\Http\Integrations\Wibutler\Requests\Login;
use App\Http\Integrations\Wibutler\WibutlerConnector;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use WebSocket\Client;

class WebSocketListen extends Command
{
    protected $signature = 'websocket:listen';
    protected $description = 'Listen to a WebSocket stream';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new WibutlerConnector();
        $token = $client->send(new Login())->json('sessionToken');
        $service = Service::query()->where('name', 'wibutler')->first();
        if (! $service) {
            return;
        }

        $url = Str::finish(str_replace(['http://', 'https://'], ['ws://', 'wss://'], $service->url), '/') . 'api/stream/' . $token;
        $options = [
            'timeout' => 60
        ];

        $client = new Client($url, $options);

        while (true) {
            $message = $client->receive();
            $messageArray = json_decode($message, true);
            $device = \App\Models\Device::query()
                ->where('foreign_id', data_get($messageArray, 'data.id'))
                ->first();
            if ($device) {
                $components = collect(data_get($messageArray, 'data.components'))
                    ->filter(fn ($component) => $component['type'] === 'switch' || $component['type'] === 'numeric')
                    ->map(fn ($component) => new Fluent($component));

                if ($components->count() > 0) {
                    foreach ($components as $component) {
                        if ($component->type === 'numeric') {
                            $device->value = $component->value;
                        } else {
                            $device->is_on = $component->value === 'ONReleased';
                        }

                        $device->save();
                        $this->info('Device updated: ' . $device->name
                            . ' value: ' . $device->value . ' | is on:' . $device->is_on
                        );
                    }
                }
            }
        }
    }
}
