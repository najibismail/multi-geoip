<?php

if (!function_exists('multigeoip')) {

   function multigeoip($ip = null)
   {
      return app('multigeoip')->ip($ip);
   }
}
