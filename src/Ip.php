<?php

namespace Najibismail\MultiGeoIP;

class Ip
{
   public static function is_ip($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_ipv4($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV4
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_ipv6($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV6
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_public_ip($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_public_ipv4($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_public_ipv6($ip = NULL): bool
   {
      return filter_var(
         $ip,
         FILTER_VALIDATE_IP,
         FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
      ) === $ip ? TRUE : FALSE;
   }

   public static function is_private_ip($ip = NULL): bool
   {
      return self::is_ip($ip) && !self::is_public_ip($ip);
   }

   public static function is_private_ipv4($ip = NULL): bool
   {
      return self::is_ipv4($ip) && !self::is_public_ipv4($ip);
   }

   public static function is_private_ipv6($ip = NULL): bool
   {
      return self::is_ipv6($ip) && !self::is_public_ipv6($ip);
   }
}
