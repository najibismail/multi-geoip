# GeoIP

[![Latest Stable Version](http://poser.pugx.org/najibismail/geoip/v)](https://packagist.org/packages/najibismail/geoip) [![Total Downloads](http://poser.pugx.org/najibismail/geoip/downloads)](https://packagist.org/packages/najibismail/geoip) [![License](http://poser.pugx.org/najibismail/geoip/license)](https://packagist.org/packages/najibismail/geoip) [![PHP Version Require](http://poser.pugx.org/najibismail/geoip/require/php)](https://packagist.org/packages/najibismail/geoip)

Get IP Information

## Features

- **Multi Providers.** Support multi ip providers.

## Requirements

- PHP: ^7.0
- Laravel: ~5.5,~5.6,~5.7,~5.8,~6.0,~7.0,~8.0
- Lumen


## Installation

```
composer require najibismail/multi-geoip
```

## Publish Config File

```
php artisan vendor:publish --provider="Najibismail\MultiGeoIP\GeoIPServiceProvider"
```

## Usage

```php
use Najibismail\MultiGeoIP\MultiGeoIP;

$ip_info = MultiGeoIP::info('{IP ADDRESS}');

```


