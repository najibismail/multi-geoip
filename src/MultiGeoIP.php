<?php

namespace Najibismail\MultiGeoIP;

class MultiGeoIP
{

   static $default_providers = ['IPApiCo', 'IPApi', 'IPWhois', 'GeoPlugin', 'IPLocation'];
   static $ip;

   /**
    * Get the IP information
    * @param string $ip
    * @return array
    */
   public static function info(string $ip): array
   {
      $providers = config('geoip.providers', self::$default_providers);

      if (config('geoip.shuffle_providers')) {
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
      ];

      /**
       * Validate private ip
       */
      if (Ip::is_private_ip($ip) === true) {
         return $data;
      }

      /**
       * Get ip information from provider. 
       * If got any providers can't use, then will skip and continue to other providers.
       * @example 1st provider api return error, then will skip 1st provider to 2nd provider
       */
      foreach ($providers as $provider) {

         try {
            $namespace = __NAMESPACE__ . "\\Providers\\$provider";
            $reader = new $namespace();

            if ($provider == 'GeoIp') {
               $reader->database('city');
            }

            $reader->setIp($ip);

            $data['ip'] = $reader->getIp();
            $data['country_code'] = $reader->getCountryCode() ?? self::try_other_providers('country_code');
            $data['country'] = $reader->getCountry() ?? self::try_other_providers('country');
            $data['city'] = $reader->getCity() ?? self::try_other_providers('city');
            $data['zip_code'] =  $reader->getZipCode() ?? self::try_other_providers('zip_code');
            $data['state'] =  $reader->getCountry() ?? self::try_other_providers('state');
            $data['timezone'] =  $reader->getTimezone() ?? self::try_other_providers('timezone');

            break;
         } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            continue;
         } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
            continue;
         } catch (\Exception $e) {
            continue;
         }
      }

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
      foreach (self::$default_providers as $provider) {

         try {
            $namespace = __NAMESPACE__ . "\\Providers\\$provider";
            $reader = new $namespace();

            if ($provider == 'GeoIp') {
               $reader->database('city');
            }

            $reader->setIp(self::$ip);

            switch ($type) {
               case 'country_code':
                  if (is_null($reader->getCountryCode())) {
                     continue;
                  }
                  $data = $reader->getCountryCode();
                  break;
               case 'country':
                  if (is_null($reader->getCountry())) {
                     continue;
                  }
                  $data = $reader->getCountry();
                  break;
               case 'city':
                  if (is_null($reader->getCity())) {
                     continue;
                  }
                  $data = $reader->getCity();
                  break;
               case 'zip_code':
                  if (is_null($reader->getZipCode())) {
                     continue;
                  }
                  $data = $reader->getZipCode();
                  break;
               case 'state':
                  if (is_null($reader->getState())) {
                     continue;
                  }
                  $data = $reader->getState();
                  break;
               case 'timezone':
                  if (is_null($reader->getTimezone())) {
                     continue;
                  }
                  $data = $reader->getTimezone();
                  break;

               default:
                  $data = null;
                  break;
            }

            return $data;
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
