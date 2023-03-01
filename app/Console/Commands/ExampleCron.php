<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExampleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command E-mail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Example Cron comando rodando com êxito');
    }
}
