<?php

namespace App\Console;

use App\Models\Cronjob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $cronjobs = Cronjob::query()->where('is_active', true)->get();

        foreach ($cronjobs as $cronjob) {
            $schedule->{$cronjob->type}(get_class(new $cronjob->command()), $cronjob->command_params ?? [])->cron($cronjob->expression);
        }

        // These are mandatory cronjobs
        $schedule->command('devices:update')->everyMinute();
        $schedule->command('telegram:sync')->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
