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
		/**
		 * TWITTER AUTHENTIFICATION
		 * ------------------------
		 */

		$apikey = 'Z0arj4wYR0TlRIcpdy4tdAvIR';
		$apisecret = '3px8MvckKG4TpaOuUysLK4j4mndgpvsfoXHxQeG3ZjfBwK3YDR';
		$accesstoken = '325628241-MkdJ62Jy2wVC12yt8jsQQdzwTR8nqnTHB1mAmDIV';
		$accesstokensecret = 'uaMveJiLbGvN4TKIBp1UdNVdyTYJS8Iabh0CMs3CZnLuY';

		TwitterAPICall::setAPIAccessTokens($apikey, $apisecret, $accesstoken, $accesstokensecret);
		
		if($_ENV['APP_ENV'] === 'production')
		{
			$schedule->call(function() {
				$job = (new TwitterAPICall())->onQueue('cron');
				dispatch($job);
			})
				->description('calling Twitter API')
				->hourly();
		}
		
		else
		{
			$schedule->call(function() {
				$job = (new TwitterAPICall())->onQueue('cron');
				dispatch($job);
			})
				->description('calling Twitter API')
				->everyMinute();
		}
    }
	
}
