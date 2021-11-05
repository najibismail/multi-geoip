<?php

namespace Najibismail\MultiGeoip\Providers;

use Exception;
use Najibismail\MultiGeoip\Helpers;

class IPApi extends Helpers
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
            $reader = unserialize($this->curl('http://ip-api.com/php/' . $this->ip));

            if (isset($reader['status']) && $reader['status'] == 'fail') {
                throw new Exception("Address Not Found");
            }

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
        if (!isset($this->reader['countryCode'])) {
            return null;
        }

        return $this->reader['countryCode'];
    }

    public function getCountry()
    {
        if (!isset($this->reader['country'])) {
            return null;
        }

        return $this->reader['country'];
    }

    public function getCity()
    {
        if (!isset($this->reader['city'])) {
            return null;
        }

        return $this->reader['city'];
    }

    public function getZipCode()
    {
        if (!isset($this->reader['zip'])) {
            return null;
        }

        return $this->reader['zip'];
    }

    public function getState()
    {
        if (!isset($this->reader['regionName'])) {
            return null;
        }

        return $this->reader['regionName'];
    }

    public function getTimezone()
    {
        if (!isset($this->reader['timezone'])) {
            return null;
        }

        return $this->reader['timezone'];
    }

    public function getRawRecord()
    {
        if (!isset($this->reader)) {
            return null;
        }

        return $this->reader;
    }
}
