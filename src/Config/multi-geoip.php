<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multi GeoIP Providers
    |--------------------------------------------------------------------------
    |
    | Here is the list of multi geoip providers. 
    | Default provider is 'all',
    | If want to run in multiple provider, put in array.
    | * Available providers: ['IPApiCo', 'IPApi', 'IPWhois', 'GeoPlugin', 'IPLocation', 'Maxmind', 'DBIp']
    |
     */
    'providers' => env('MULTI_GEOIP_PROVIDERS', 'all'),

    /*
    |--------------------------------------------------------------------------
    | Multi GeoIP Shuffle Providers
    |--------------------------------------------------------------------------
    |
    | Shuffle providers
    |
     */
    'shuffle_providers' => env('MULTI_GEOIP_SHUFFLE_PROVIDERS', true),

    /*
    |--------------------------------------------------------------------------
    | Free Maxmind GeoIP Database
    |--------------------------------------------------------------------------
    |
    |
     */
    'maxmind' => [
        'path' => env('MULTI_GEOIP_MAXMIND_PATH', storage_path('maxmind')),
        'database' => env('MULTI_GEOIP_MAXMIND_DB', 'city'),
    ],
];
