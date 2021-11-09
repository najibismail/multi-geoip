<?php

namespace Najibismail\MultiGeoip\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Najibismail\MultiGeoip\BaseMultiGeoip;

class MaxmindCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multigeoip:maxmind-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download free maxmind GeoLite2 db';

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
    public function handle(BaseMultiGeoip $basemultigeoip)
    {
        $this->output->writeln(PHP_EOL);
        $this->output->writeln('<info>Downloading maxmind db.....</info>' . PHP_EOL);

        $maxmind = $basemultigeoip->maxmind();

        if (isset($maxmind->assets)) {
            $bar = $this->output->createProgressBar(collect($maxmind->assets)->count());
            foreach ($maxmind->assets as $asset) {
                try {
                    $storage_folder = config('multi-geoip.maxmind.path');

                    if (!File::isDirectory($storage_folder)) {
                        File::makeDirectory($storage_folder, 0755, true, true);
                    }
                    $bar->advance();
                    copy($asset->browser_download_url,  $storage_folder . '/' . $asset->name);
                } catch (\Exception $e) {
                    continue;
                }
            }

            $bar->finish();
        }
        $this->output->writeln(PHP_EOL);
    }
}
