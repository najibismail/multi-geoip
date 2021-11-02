<?php

namespace Najibismail\MultiGeoIP;

use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/multi-geoip.php', 'multi-geoip');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require_once __DIR__ . '/Helpers.php';
        
        $this->publishes([
            __DIR__ . '/Config/multi-geoip.php' => config_path('multi-geoip.php'),
        ]);
    }
}
