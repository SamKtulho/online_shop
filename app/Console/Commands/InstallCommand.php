<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:shop-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shop installation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('storage:link');
        $this->call('migrate');

        return self::SUCCESS;
    }
}
