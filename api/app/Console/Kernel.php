<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Jobs\TwitterApiCall;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		if($_ENV['APP_ENV'] === 'production')
		{
			echo 'scheduling hourly twitter api calls';
			$schedule->job(new TwitterAPICall)->hourly();
		}
		else
		{
			echo 'scheduling twitter api calls every minute';
			$schedule->job(new TwitterAPICall)->everyMinute();
		}
    }
}
