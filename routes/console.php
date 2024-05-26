<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateStudentStatusToExpired;


Schedule::call(UpdateStudentStatusToExpired::class)->monthlyOn(1, '00:05')->description('(매달 1일, 00:15)');

Schedule::call(function () {
    $this->call('scheduler:academy-status-reserve'); // 학원 상태 변경
    $this->call('scheduler:branch-settlement'); //지사 정산
})->monthlyOn(1, '00:05')->description('(매달 1일, 00:15)');

