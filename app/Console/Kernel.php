<?php

namespace FilmsOnYoutube\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    \FilmsOnYoutube\Console\Commands\Inspire::class,
    'FilmsOnYoutube\Console\Commands\FetchLinks',
    'FilmsOnYoutube\Console\Commands\CheckLink'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('links:fetch')->everyThirtyMinutes();
        $schedule->command('links:check')->cron('*/1 * * * *');
    }

}
