<?php

if (!function_exists('multigeoip')) {

   function multigeoip($ip = null)
   {
      if (is_null($ip)) {
         return app('multigeoip');
      }

      return app('multigeoip')->ip($ip);
   }
}
