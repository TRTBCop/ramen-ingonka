<?php

namespace App\Console;

use App\Console\Commands\UpdateStudentStatusToExpired;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(UpdateStudentStatusToExpired::class)->monthlyOn(1, '00:05')->description('(매달 1일, 00:15)');

        $schedule->call(function () {
            $this->call('scheduler:academy-status-reserve'); // 학원 상태 변경
        })->monthlyOn(1, '00:05')->description('(매달 1일, 00:15)');

        /*
        $schedule->call(function () {
            $this->call('scheduler:test1'); // 테스트1
            $this->call('scheduler:test2'); // 테스트2
        })->everyMinute();
        */
        $schedule->command('scheduler:test1')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
