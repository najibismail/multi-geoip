# Multi GeoIP

[![Latest Stable Version](http://poser.pugx.org/najibismail/multigeoip/v)](https://packagist.org/packages/najibismail/multigeoip) [![Total Downloads](http://poser.pugx.org/najibismail/multigeoip/downloads)](https://packagist.org/packages/najibismail/multigeoip) [![Latest Unstable Version](http://poser.pugx.org/najibismail/multigeoip/v/unstable)](https://packagist.org/packages/najibismail/multigeoip) [![License](http://poser.pugx.org/najibismail/multigeoip/license)](https://packagist.org/packages/najibismail/multigeoip) [![PHP Version Require](http://poser.pugx.org/najibismail/multigeoip/require/php)](https://packagist.org/packages/najibismail/multigeoip)

Get IP address information from multi providers

## Features

- **Multi IP Providers.** Support get the ip information from multi ip providers.

## Requirements

- PHP: ^7.0
- Laravel: ~5.5,~5.6,~5.7,~5.8,~6.0,~7.0,~8.0


## Installation

```
composer require najibismail/multigeoip
```

## Publish Config File

```
php artisan vendor:publish --provider="Najibismail\MultiGeoIP\GeoIPServiceProvider"
```

## Download Free Maxmind DB

```
php artisan multigeoip:maxmind-db
```
## Usage

```php
use Najibismail\MultiGeoIP\MultiGeoIP;

$ip_info = MultiGeoIP::info('{IP ADDRESS}');

```

## Disclaimer
MultiGeoIP uses the GeoLite database from MaxMind.
Use of MultiGeoIP services making use of geolocation data is under condition of acceptance of the <a href="http://creativecommons.org/licenses/by-sa/3.0/" class="urlextern" target="_new" title="http://creativecommons.org/licenses/by-sa/3.0/" rel="nofollow">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>. The attribution requirement may be met by including the following in all advertising and documentation mentioning features of or use of this database: 

```html
This product includes GeoLite data created by MaxMind, available from
<a href="http://www.maxmind.com">http://www.maxmind.com</a>.
```

