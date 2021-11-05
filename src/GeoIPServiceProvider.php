<?php

namespace Najibismail\MultiGeoIP;

use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
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
        }
        
        $this->publishes([
            self::CONFIG_PATH => config_path('multi-geoip.php'),
        ]);
    }
}
