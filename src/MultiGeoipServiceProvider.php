<?php

namespace Najibismail\MultiGeoip;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class MultiGeoipServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/Config/multi-geoip.php';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'multi-geoip');

        $this->app->bind('multigeoip', function () {
            return new Multigeoip();
        });

        $this->app->alias('multigeoip', Multigeoip::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MaxmindCommand::class,
                Commands\PublishCommand::class,
            ]);

            $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command(Commands\MaxmindCommand::class)->dailyAt('05:00');
            });
        }

        $this->publishes([
            self::CONFIG_PATH => config_path('multi-geoip.php'),
        ]);
    }
}
