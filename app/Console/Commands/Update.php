<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\Service;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates device states and imports new devices from services';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Service::query()->whereNotNull('controller')->get() as $service) {
            $ctrl = new $service->controller;
            if (method_exists($ctrl, 'import')) {
                try {
                    $ctrl->import($service);
                } catch (\Exception $e) {

                }
            }
        }

        foreach (Device::query()->where('is_group', true)->get() as $device) {
            $device->value = $device->children()?->avg('value') ?? 0;
            $device->is_on = (bool)$device->children()?->sum('value') ?? false;
            $device->save();
        }
        return 0;
    }
}
