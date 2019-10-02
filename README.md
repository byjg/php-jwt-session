# JwtSession

[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg.com-brightgreen.svg)](http://opensource.byjg.com)
[![Build Status](https://travis-ci.org/byjg/jwt-session.svg?branch=master)](https://travis-ci.org/byjg/jwt-session)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/byjg/jwt-session/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/byjg/jwt-session/?branch=master)

JwtSession is a PHP session replacement. Instead of use FileSystem, just use JWT TOKEN. 
The implementation following the SessionHandlerInterface.

# How to use:

Before the session_start() use the command: 

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key');

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

Now, all your `$_SESSION` variable will be saved directly to a JWT Token!!

## Secret key
Make sure that you are providing a base64url encoded key.
 
# Motivation

The default PHP Session does not work in different servers using round robin or other algorithms.
This occurs because PHP Session are saved by default in the file system. 

There are implementations can save the session to REDIS or MEMCACHED, for example. 
But this requires to you create a new server to store this session and creates a single point of failure. 
To avoid this you have to create REDIS/MEMCACHED clusters. 

But if you save the session into JWT Token you do not need to create a new server.
Just to use. 

You can read more in this Codementor's article: 
[Using JSON Web Token (JWT) as a PHP Session](https://www.codementor.io/byjg/using-json-web-token-jwt-as-a-php-session-axeuqbg1m)

# Security Information

The JWT Token cannot be changed, but it can be read. 
This implementation save the JWT into a client cookie.  
Because of this _**do not** store in the JWT Token sensible data like passwords_.
 
# Install

```
composer require "byjg/jwt-session=2.0.*"
```

 
# Setting the validity of JWT Token

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withTimeoutMinutes(60);   // You can use withTimeoutHours(1)

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

# Setting the different Session Contexts

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withSessionContext('MYCONTEXT');

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

# Create the handler and replace the session handler

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

# Specify cookie domain 

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withCookie('.mydomain.com', '/')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

# Uses RSA Private/Public Keys

```php
<?php
        $secret = <<<PRIVATE
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA5PMdWRa+rUJmg6QMNAPIXa+BJVN7W0vxPN3WTK/OIv5gxgmj
2inHGGc6f90TW/to948LnqGtcD3CD9KsI55MubafwBYjcds1o9opZ0vYwwdIV80c
OVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNxcRK38tOCApjZySx/NzMDeaXuWe+1
nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIklNnyq4TfAUSwl+KN/zjj3CXad1oDT
7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLnJW1WcLlAAIaAfABtSZboznsStMnY
to01wVknXKyERFs7FLHYqKQANIvRhFTptsehowIDAQABAoIBAEkJkaQ5EE0fcKqw
K8BwMHxKn81zi1e9q1C6iEHgl8csFV03+BCB4WTUkaH2udVPJ9ZJyPArLbQvz3fS
wl1+g4V/UAksRtRslPkXgLvWQ2k8KoTwBv/3nn9Kkozk/h8chHuii0BDs30yzSn4
SdDAc9EZopsRhFklv9xgmJjYalRk02OLck73G+d6MpDqX56o2UA/lf6i9MV19KWP
HYip7CAN+i6k8gA0KPHwr76ehgQ6YHtSntkWS8RfVI8fLUB1UlT3HmLgUBNXMWkQ
ZZbvXtNOt6NtW/WIAHEYeE9jmFgrpW5jKJSLn5iGVPFZwJIZXRPyELEs9NHWkS6e
GmdzxnECgYEA8+m05B/tmeZOuMrPVJV9g+aBDcuxmW+sdLRch+ccSmx4ZNQOLVoU
klYgTZq/a1O4ENq0h2WgccNlRHdcH4sXMBvLalA/tFhZMUuA/KXWyZ1F0hBnjHVF
cj1alHCqh+9qJDGdn4mxSmrp8p0rfeWgBwlFtJEJmjjDWDCtVY+JZcsCgYEA8EuV
WF/ilgDjgC4jMCYNuO0oFGBbtNP17PuU3kh8W+joqK/nufZ3NLy1WrDIpqa9YPex
328Nnjljf5GJWSdMchAp82waLzl7FaaBTY0iyFAK4J0jfC/fVLx82+wpM3utDnh8
9x5iIboO5U7uEJ7k8X2p64GoprlKJSRmGAJ7eIkCgYEAw5IsXI3NMY0cqcbUHvoO
PehgqfMdX+3O1XSYjM+eO35lulLdWzfTLtKn7BGcUi46dCkofzfZQd5uIEukLhaU
bRqcK45UxgHg4kmsDufaJKZaCWjl3hVZrZPMQSFlWsF41bSCshzxbr3y/3lOGhA4
E+w3W+S/Uk0ZNGkzUltYy6kCgYEA0gRNeBr9z7rhG4O3j3qC3dCxCfYZ0Na8hy5v
M0PJJQ9QYTa04iyOjVItcyE1jaoHtLtoA+9syJBB7RoHIBufzcVg1Pbzf7jOYeLP
+jbTYp3Kk/vjKsQwfj/rJM+oRu3eF9qo5dbxT6btI++zVGV7lbEOFN6Sx30EV6gT
bwKkZXkCgYEAnEtN43xL8bRFybMc1ZJErjc0VocnoQxCHm7LuAtLOEUw6CwwFj9Q
GOl+GViVuDHUNQvURLn+6gg4tAemYlob912xIPaU44+lZzTMHBOJBGMJKi8WogKi
V5+cz9l31uuAgNfjL63jZPaAzKs8Zx6R3O5RuezympwijCIGWILbO2Q=
-----END RSA PRIVATE KEY-----
PRIVATE;

        $public = <<<PUBLIC
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5PMdWRa+rUJmg6QMNAPI
Xa+BJVN7W0vxPN3WTK/OIv5gxgmj2inHGGc6f90TW/to948LnqGtcD3CD9KsI55M
ubafwBYjcds1o9opZ0vYwwdIV80cOVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNx
cRK38tOCApjZySx/NzMDeaXuWe+1nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIkl
Nnyq4TfAUSwl+KN/zjj3CXad1oDT7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLn
JW1WcLlAAIaAfABtSZboznsStMnYto01wVknXKyERFs7FLHYqKQANIvRhFTptseh
owIDAQAB
-----END PUBLIC KEY-----
PUBLIC;

$sessionConfig = (new \ByJG\Session\SessionConfig('example.com'))
    ->withRsaSecret($secret, $public)
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

If you want to know more details about how to create RSA Public/Private Keys access:
https://github.com/byjg/jwt-wrapper 


# How it works

We store a cookie named AUTH_BEARER_<context name> with the session name. The PHPSESSID cookie is still created because
PHP create it by default but we do not use it;


----
[Open source ByJG](http://opensource.byjg.com)
