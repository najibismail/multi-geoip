<?php

namespace Najibismail\MultiGeoip\Providers;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Najibismail\MultiGeoip\BaseMultiGeoip;
use Illuminate\Support\Facades\File;


class Maxmind extends BaseMultiGeoip
{
    protected $record;
    protected $ip;
    protected $reader;
    protected $database_type; // 'city' | 'country' | 'asn'

    /**
     * Choose Database
     * @string $database_type
     */
    public function database($database_type = 'city')
    {
        $this->database_type = strtolower($database_type);

        $db_name = 'GeoLite2-' . ucfirst($database_type) . '.mmdb';
        $db_path = config('multi-geoip.maxmind.path') . '/' . $db_name;
        if (!File::exists($db_path)) {
            $maxmind = $this->maxmind();

            if (isset($maxmind->assets)) {

                foreach ($maxmind->assets as $asset) {
                    try {
                        $storage_folder = config('multi-geoip.maxmind.path');

                        if (!File::isDirectory($storage_folder)) {
                            File::makeDirectory($storage_folder, 0755, true, true);
                        }
                        copy($asset->browser_download_url,  $storage_folder . '/' . $asset->name);
                    } catch (\Exception $e) {
                        throw new AddressNotFoundException("Unable to download maxmind db");
                    }
                }
            }
        }

        $this->reader = new Reader($db_path);
    }

    /**
     * Setting Custom IP
     * @param $ip
     */
    public function setIp($ip = null)
    {
        $reader = $this->reader;

        if (is_null($ip)) {
            $ip = $this->get_client_ip();
        }

        $this->ip = $ip;

        if ($ip == '127.0.0.1') {
            throw new AddressNotFoundException("127.0.0.1 is not supported");
        }

        try {
            $this->record = $reader->{$this->database_type}($this->ip);
        } catch (\Exception $e) {
            throw new AddressNotFoundException($e->getMessage());
        }
    }

    /**
     * getIp
     * @return Ip Address
     */
    public function getIp()
    {
        if (!isset($this->ip)) {
            return null;
        }

        return $this->ip;
    }

    /**
     * getState()
     * @return state
     */
    public function getState()
    {
        if (!isset($this->record->mostSpecificSubdivision)) {
            return null;
        }

        if (!isset($this->record->mostSpecificSubdivision->name)) {
            return null;
        }

        return $this->record->mostSpecificSubdivision->name;
    }

    /**
     * getState()
     * @return country code "TZ etc"
     */
    public function getCountryCode()
    {
        if (!isset($this->record->country)) {
            return null;
        }

        if (!isset($this->record->country->isoCode)) {
            return null;
        }

        return $this->record->country->isoCode;
    }

    /**
     * getCity()
     * @return city name
     */
    public function getCity()
    {
        if (!isset($this->record->city)) {
            return null;
        }

        if (!isset($this->record->city->name)) {
            return null;
        }

        return $this->record->city->name;
    }

    /**
     * getZipCode()
     * @return Zip Code (#)
     */
    public function getZipCode()
    {
        if (!isset($this->record->postal)) {
            return null;
        }

        if (!isset($this->record->postal->code)) {
            return null;
        }

        return $this->record->postal->code;
    }

    /**
     * getCountryName
     * @return CountryName
     */
    public function getCountry()
    {
        if (!isset($this->record->raw['country'])) {
            return null;
        }

        if (!isset($this->record->raw['country']['names'])) {
            return null;
        }

        if (!isset($this->record->raw['country']['names']['en'])) {
            return null;
        }

        return $this->record->raw['country']['names']['en'];
    }

    /**
     * getTimeZone
     *
     * @return Timezone e.g "Asia/Kuala_Lumpur"
     */
    public function getTimezone()
    {

        if (!isset($this->record->raw['location'])) {
            return null;
        }

        if (!isset($this->record->raw['location']['time_zone'])) {
            return null;
        }

        return $this->record->raw['location']['time_zone'];
    }

    /**
     * getRawRecord()
     * (if you want to manually extract objects)
     *
     * @return object of all items
     */
    public function getRawRecord()
    {
        if (!isset($this->record)) {
            return null;
        }

        return $this->record;
    }
}
