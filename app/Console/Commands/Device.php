<?php

namespace App\Console\Commands;

use App\Http\Controllers\DeviceController;
use Illuminate\Console\Command;

class Device extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device {device} {--command=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cmd = $this->option('command');
        $device = \App\Models\Device::query()->whereKey($this->argument('device'))->first();

        $ctrl = new DeviceController();
        $ctrl->{$cmd}($device);
        return 0;
    }
}
