<?php

namespace Najibismail\MultiGeoip\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multigeoip:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes the multigeoip config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('[MultiGeoIP] Publishing config ...');
        $this->call('vendor:publish', [
            '--provider' => 'Najibismail\MultiGeoip\GeoIPServiceProvider'
        ]);
    }
}
