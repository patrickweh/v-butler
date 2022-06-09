<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;

class MqttDeamon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $matches = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $services = Service::query()->where('config', 'LIKE', '%mqtt_topic%')->get();
        foreach ($services as $service) {#
            $topic = rtrim($service->config['mqtt_topic'], '/');
            $devices = $service->devices;
            foreach ($devices as $device) {
                $this->matches[$topic . '/' . ltrim($device->foreign_id,'/')] = $device;
            }

        }

        $mqtt = MQTT::connection();
        $mqtt->subscribe('#', function (string $topic, string $message) {
            $device = $this->matches[$topic] ?? null;
            if ($device) {
                $this->info($device->id . ' - ' . $device->name . ' -> ' . $message);
                $device->value = $message;
                $device->save();
            }
            $this->error($topic . ' skipped');
        }, 1);

        $mqtt->loop(true, true);
        return 0;
    }
}
