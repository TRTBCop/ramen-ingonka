<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateStudentStatusToExpired extends Command
{
    /**
     * 학생서비스 상태만료
     *
     * @var string
     */
    protected $signature = 'command:update-students-status-to-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '학생의 서비스 상태 변경';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
