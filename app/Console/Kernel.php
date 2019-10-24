<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Traits\NotificationCustomFunctions;

class Kernel extends ConsoleKernel
{
	use NotificationCustomFunctions;
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
        $schedule->call(function () {
            $this->commonFunctionToPush('2','','', '', '');
			$this->commonFunctionToPush('3','','', '', '');
			$this->commonFunctionToPush('8','','', '', '');
			$this->commonFunctionToPush('9','','', '', '');
        })->everyMinute();
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
