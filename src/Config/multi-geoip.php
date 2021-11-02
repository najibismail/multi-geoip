<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GeoIP Providers
    |--------------------------------------------------------------------------
    |
    | Here is the list of geoip providers
    |
     */
    'providers' => env('GEOIP_PROVIDERS', ['IPApiCo', 'IPApi', 'IPWhois', 'GeoPlugin', 'IPLocation']),

    /*
    |--------------------------------------------------------------------------
    | GeoIP Shuffle Providers
    |--------------------------------------------------------------------------
    |
    | Shuffle the providers to get the different providers
    |
     */
    'shuffle_providers' => env('GEOIP_SHUFFLE_PROVIDERS', true),
];
