<?php

namespace Najibismail\MultiGeoIP\Providers;

use Exception;
use Najibismail\MultiGeoIP\Helpers;

class IPApiCo extends Helpers
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
            $reader = json_decode($this->curl('https://ipapi.co/' . $this->ip . '/json/'), true);
            if (isset($reader['error']) && $reader['error'] == true) {
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
        if (!isset($this->reader['country_code'])) {
            return null;
        }

        return $this->reader['country_code'];
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
        if (!isset($this->reader['postal'])) {
            return null;
        }

        return $this->reader['postal'];
    }

    public function getState()
    {
        if (!isset($this->reader['region'])) {
            return null;
        }

        return $this->reader['region'];
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
