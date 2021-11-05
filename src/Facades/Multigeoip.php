<?php

namespace Najibismail\MultiGeoip\Facades;

use Illuminate\Support\Facades\Facade;

class Multigeoip extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'multigeoip';
    }
}