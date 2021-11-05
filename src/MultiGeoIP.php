<?php

namespace Najibismail\MultiGeoip;

class Multigeoip
{
    private $result;
    private $ip;

    const DEFAULT_PROVIDERS = [
        'IPApiCo',
        'IPApi',
        'IPWhois',
        'GeoPlugin',
        'IPLocation',
        'Maxmind'
    ];

    public function __construct()
    {
        $this->result = [
            'ip' => null,
            'country_code' => null,
            'country' => null,
            'city' => null,
            'zip_code' => null,
            'state' => null,
            'timezone' => null,
            'response_time' => null,
            'provider' => null,
        ];
    }

    /**
     * Set ip address
     *
     * @param string $ip
     * @return void
     */
    public function ip(string $ip)
    {
        /**
         * Validate private ip
         */
        if (Ip::is_private_ip($ip) === true) {
            return $this;
        }

        $providers = config('multi-geoip.providers');

        if (!is_array($providers) || is_null($providers) || ($providers == 'all')) {

            if (is_string($providers) && in_array($providers, self::DEFAULT_PROVIDERS)) {
                $providers = [$providers];
            } else {
                $providers = self::DEFAULT_PROVIDERS;
            }
        }

        if (config('multi-geoip.shuffle_providers') && is_array($providers)) {
            shuffle($providers);
        }

        $this->ip = $ip;

        $start = microtime(true);

        /**
         * Get ip information from provider. 
         * If got any providers can't use, then will skip and continue to other providers.
         * @example 1st provider api return error, then will skip 1st provider and go to 2nd provider
         */

        if (is_array($providers)) {
            foreach ($providers as $provider) {

                try {
                    $namespace = __NAMESPACE__ . "\\Providers\\$provider";
                    $reader = new $namespace();

                    if ($provider == 'Maxmind') {
                        $reader->database(config('multi-geoip.maxmind.database'));
                    }

                    $reader->setIp($ip);

                    $this->result['ip'] = $reader->getIp();
                    $this->result['country_code'] = $reader->getCountryCode() ?? $this->try_other_providers('getCountryCode');
                    $this->result['country'] = $reader->getCountry() ?? $this->try_other_providers('getCountry');
                    $this->result['city'] = $reader->getCity() ?? $this->try_other_providers('getCity');
                    $this->result['zip_code'] =  $reader->getZipCode() ?? $this->try_other_providers('getZipCode');
                    $this->result['state'] =  $reader->getCountry() ?? $this->try_other_providers('getCountry');
                    $this->result['timezone'] =  $reader->getTimezone() ?? $this->try_other_providers('getTimezone');
                    $this->result['provider'] = $provider;
                    break;
                } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
                    continue;
                } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
                    continue;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $this->result['response_time'] = number_format(microtime(true) - $start, 3) . ' seconds';

        return $this;
    }

    private function try_other_providers($type): string
    {
        $providers = self::DEFAULT_PROVIDERS;
        shuffle($providers);

        foreach ($providers as $provider) {

            try {
                $namespace = __NAMESPACE__ . "\\Providers\\$provider";
                $reader = new $namespace();

                if ($provider == 'Maxmind') {
                    $reader->database(config('multi-geoip.maxmind.database'));
                }

                $reader->setIp($this->ip);

                if (is_null($reader->{$type}())) {
                    continue;
                }
                
                return $reader->{$type}();
                break;
            } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
                continue;
            } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
                continue;
            } catch (\Exception $e) {
                continue;
            }
        }
    }


    public function all(): array
    {
        return $this->result;
    }

    public function getIp()
    {
        return $this->result['ip'];
    }

    public function getCountryCode()
    {
        return $this->result['country_code'];
    }
    public function getCountry()
    {
        return $this->result['country'];
    }
    public function getCity()
    {
        return $this->result['city'];
    }
    public function getZipCode()
    {
        return $this->result['zip_code'];
    }
    public function getState()
    {
        return $this->result['state'];
    }
    public function getTimezone()
    {
        return $this->result['timezone'];
    }
}
