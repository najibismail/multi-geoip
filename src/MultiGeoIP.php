<?php

namespace Najibismail\MultiGeoIP;

class MultiGeoIP
{

   const DEFAULT_PROVIDERS = ['IPApiCo', 'IPApi', 'IPWhois', 'GeoPlugin', 'IPLocation', 'Maxmind'];
   static $ip;

   /**
    * Get the IP information
    * @param string $ip
    * @return array
    */
   public static function info(string $ip): array
   {
      $providers = config('multi-geoip.providers');

      if (sizeof($providers) == 0) {
         $providers = self::DEFAULT_PROVIDERS;
      }

      if (config('multi-geoip.shuffle_providers')) {
         shuffle($providers);
      }

      self::$ip = $ip;

      $data = [
         'ip' => null,
         'country_code' => null,
         'country' => null,
         'city' => null,
         'zip_code' => null,
         'state' => null,
         'timezone' => null,
         'response_time' => null,
      ];

      /**
       * Validate private ip
       */
      if (Ip::is_private_ip($ip) === true) {
         return $data;
      }

      $start = microtime(true);

      /**
       * Get ip information from provider. 
       * If got any providers can't use, then will skip and continue to other providers.
       * @example 1st provider api return error, then will skip 1st provider to 2nd provider
       */
      foreach ($providers as $provider) {

         try {
            $namespace = __NAMESPACE__ . "\\Providers\\$provider";
            $reader = new $namespace();

            if ($provider == 'Maxmind') {
               $reader->database(config('multi-geoip.maxmind.database'));
            }

            $reader->setIp($ip);

            $data['ip'] = $reader->getIp();
            $data['country_code'] = $reader->getCountryCode() ?? self::try_other_providers('getCountryCode');
            $data['country'] = $reader->getCountry() ?? self::try_other_providers('getCountry');
            $data['city'] = $reader->getCity() ?? self::try_other_providers('getCity');
            $data['zip_code'] =  $reader->getZipCode() ?? self::try_other_providers('getZipCode');
            $data['state'] =  $reader->getCountry() ?? self::try_other_providers('getCountry');
            $data['timezone'] =  $reader->getTimezone() ?? self::try_other_providers('getTimezone');

            break;
         } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            continue;
         } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
            continue;
         } catch (\Exception $e) {
            continue;
         }
      }

      $data['response_time'] = number_format(microtime(true) - $start, 3) . ' seconds';
      return $data;
   }

   /**
    * Try other provider if have null value
    *
    * @param [type] $type
    * @return string
    */
   private static function try_other_providers($type): string
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

            $reader->setIp(self::$ip);

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
}
