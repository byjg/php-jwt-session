# JwtSession

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/byjg/jwt-session/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/byjg/jwt-session/?branch=master)

JwtSession is a PHP session replacement. Instead of use FileSystem, just use JWT TOKEN. 
The implementation following the SessionHandlerInterface.

## How to use:

Before the session_start() use the command: 

```php
<?php
$handler = new \ByJG\Session\JwtSession('your.domain.com', 'your super secret key');
session_set_save_handler($handler, true);
```

Now, all your `$_SESSION` variable will be saved directly to a JWT Token!!
 
## Motivation

The default PHP Session does not work in different servers using round robin or other algorithms.
This occurs because PHP Session are saved by default in the file system. 

There are implementations can save the session to REDIS or MEMCACHED, for example. 
But this requires to you create a new server to store this session and creates a single point of failure. 
To avoid this you have to create REDIS/MEMCACHED clusters. 

But if you save the session into JWT Token you do not need to create a new server.
Just to use. 

You can read more in this Codementor's article: 
[Using JSON Web Token (JWT) as a PHP Session](https://www.codementor.io/byjg/using-json-web-token-jwt-as-a-php-session-axeuqbg1m)

## Security Information

The JWT Token cannot be changed, but it can be read. 
This implementation save the JWT into a client cookie.  
Because of this _**do not** store in the JWT Token sensible data like passwords_.
 
## Install

```
composer require "byjg/jwt-session=1.0.*"
```

## Customizations
 
### Setting the validity of JWT Token

```php
<?php
// Setting to 50 minutes
$handler = new \ByJG\Session\JwtSession('your.domain.com', 'your super secret key', 50);
session_set_save_handler($handler, true);
```

### Setting the different Session Contexts

```php
<?php
$handler = new \ByJG\Session\JwtSession('your.domain.com', 'your super secret key', 20, 'MYCONTEXT');
session_set_save_handler($handler, true);
```

### Create the handler and replace the session handler

```php
<?php
$handler = new \ByJG\Session\JwtSession('your.domain.com', 'your super secret key');
$handler->replaceSessionHandler(true);
```

### Create the handler and replace the session handler, specifying cookie domain valid for all subdomains of mydomain.com

```php
<?php
$handler = new \ByJG\Session\JwtSession('your.domain.com', 'your super secret key', null, null, '.mydomain.com');
$handler->replaceSessionHandler(true);
```

### How it works

We store a cookie named AUTH_BEARER_<context name> with the session name. The PHPSESSID cookie is still created because
PHP create it by default but we do not use it;