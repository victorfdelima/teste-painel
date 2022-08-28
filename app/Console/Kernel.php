<?php

namespace App\Console;

use App\Jobs\ProvidersUpdateFleet;
use App\Jobs\UpdateRequestCity;
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
        \App\Console\Commands\CustomCommand::class,
        \App\Console\Commands\DbClearCommand::class,
        \App\Console\Commands\ProviderCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cronjob:rides')
                ->everyMinute();

//        $schedule->command('cronjob:providers')
//                ->everyMinute();

        $schedule->job(new ProvidersUpdateFleet())
            ->everyMinute();

//        $schedule->job(new UpdateRequestCity())
//            ->everyMinute();

        $schedule->call('App\Http\Controllers\AdminController@DBbackUp')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
