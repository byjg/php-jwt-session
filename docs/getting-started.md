---
sidebar_position: 1
---

# Getting Started

JwtSession is a PHP session replacement. Instead of using the FileSystem, it uses JWT TOKEN. The implementation follows the SessionHandlerInterface.

## Installation

Install via Composer:

```bash
composer require "byjg/jwt-session"
```

## Basic Usage

Before calling `session_start()`, configure and set up the JwtSession handler:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key');

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

Now, all your `$_SESSION` variables will be saved directly to a JWT Token!

## Secret Key

Make sure that you are providing a base64url encoded key.

## Motivation

The default PHP Session does not work in different servers using round robin or other algorithms. This occurs because PHP Sessions are saved by default in the file system.

There are implementations that can save the session to REDIS or MEMCACHED, for example. But this requires you to create a new server to store this session and creates a single point of failure. To avoid this you have to create REDIS/MEMCACHED clusters.

But if you save the session into JWT Token you do not need to create a new server. Just use it.

You can read more in this Codementor's article:
[Using JSON Web Token (JWT) as a PHP Session](https://www.codementor.io/byjg/using-json-web-token-jwt-as-a-php-session-axeuqbg1m)
