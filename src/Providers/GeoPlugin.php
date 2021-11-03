<?php

namespace Najibismail\MultiGeoIP\Providers;

use Exception;
use Najibismail\MultiGeoIP\Helpers;

class GeoPlugin extends Helpers
{

    protected $ip;
    protected $reader;


    public function setIp($ip = null)
    {

        if (is_null($ip)) {
            $ip = $this->get_client_ip();
        }

        $this->ip = $ip;

        try {

            $reader = json_decode($this->curl('http://www.geoplugin.net/json.gp?ip=' . $this->ip), true);
            $this->reader = $reader;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getIp()
    {
        if (!isset($this->ip)) {
            return null;
        }

        return $this->ip;
    }

    public function getCountryCode()
    {
        if (!isset($this->reader['geoplugin_countryCode'])) {
            return null;
        }

        return $this->reader['geoplugin_countryCode'];
    }

    public function getCountry()
    {
        if (!isset($this->reader['geoplugin_countryName'])) {
            return null;
        }

        return $this->reader['geoplugin_countryName'];
    }

    public function getCity()
    {
        if (!isset($this->reader['geoplugin_city'])) {
            return null;
        }

        return $this->reader['geoplugin_city'];
    }

    public function getZipCode()
    {
        if (!isset($this->reader['postal'])) {
            return null;
        }

        return $this->reader['postal'];
    }

    public function getState()
    {
        if (!isset($this->reader['geoplugin_region'])) {
            return null;
        }

        return $this->reader['geoplugin_region'];
    }

    public function getTimezone()
    {
        if (!isset($this->reader['geoplugin_timezone'])) {
            return null;
        }

        return $this->reader['geoplugin_timezone'];
    }

    public function getRawRecord()
    {
        if (!isset($this->reader)) {
            return null;
        }

        return $this->reader;
    }
}
