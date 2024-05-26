<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class ScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script {file} {--sample} {--filter=} {--option=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '스크립트 실행';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $file = $this->argument('file');

        if (file_exists(app_path().'/../script/'.$file.'.php')) {
            Artisan::call('db:seed', [
                '--class' => 'Script\\'.str_replace('/','\\',$file)
            ], new ConsoleOutput());
        } else {
            $this->error('스크립트 파일을 찾을수 없습니다');
        }
    }
}
