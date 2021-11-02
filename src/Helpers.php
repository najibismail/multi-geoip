<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

if (!function_exists('curl')) {
   function curl($url, $method = 'GET', $data = [])
   {
      $client = new Client();
      try {
         $res = $client->request($method, $url, $data);
         return $res->getBody()->getContents();
      } catch (GuzzleException $g) {
         throw new Exception("Unable to call");
      }
   }
}

if (!function_exists('get_client_ip')) {
   function get_client_ip()
   {
      $server_envs = [
         'HTTP_X_ORIGINAL_FORWARDED_FOR',
         'HTTP_X_REAL_IP',
         'HTTP_CLIENT_IP',
         'HTTP_X_FORWARDED_FOR',
         'HTTP_X_FORWARDED',
         'HTTP_FORWARDED_FOR',
         'HTTP_FORWARDED',
         'REMOTE_ADDR',
      ];

      $ipaddress = 'UNKNOWN';
      foreach ($server_envs as $server_env) {
         if (isset($_SERVER["{$server_env}"])) {
            $clientip = $_SERVER["{$server_env}"];
            $ipaddress = trim(explode(',', $clientip)[0]);
            break;
         }
      }
      return $ipaddress;
   }
}
