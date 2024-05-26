<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        activity()->disableLogging();

        // 공통으로 사용될 로직 추가
        //    \Illuminate\Support\Facades\DB::disableQueryLog();
    }
}
