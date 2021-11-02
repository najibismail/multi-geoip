<?php

namespace Najibismail\MultiGeoIP\Providers;

use Exception;

class IPLocation
{

    protected $ip;
    protected $reader;

    public function setIp($ip = null)
    {

        if (is_null($ip)) {
            $ip = get_client_ip();
        }

        $this->ip = $ip;

        try {
            $reader = json_decode(curl('https://api.iplocation.net/?ip=' . $this->ip), true);

            if (isset($reader['response_code"']) && $reader['response_code"'] != '200') {
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
        if (!isset($this->reader['country_code2'])) {
            return null;
        }

        return $this->reader['country_code2'];
    }

    public function getCountry()
    {
        if (!isset($this->reader['country_name'])) {
            return null;
        }

        return $this->reader['country_name'];
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
