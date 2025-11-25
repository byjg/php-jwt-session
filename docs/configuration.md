---
sidebar_position: 2
---

# Configuration

The `SessionConfig` class provides a fluent interface for configuring JWT sessions.

## Setting the Validity of JWT Token

You can set the token timeout in minutes or hours:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withTimeoutMinutes(60);   // You can use withTimeoutHours(1)

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

## Setting Different Session Contexts

You can create multiple independent session contexts:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withSessionContext('MYCONTEXT');

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

## Replace Session Handler Automatically

You can automatically replace the session handler and start the session:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

The `replaceSessionHandler()` method accepts an optional parameter:
- `replaceSessionHandler(true)` - Replace the handler and automatically start the session (default)
- `replaceSessionHandler(false)` - Only replace the handler without starting the session

## Specify Cookie Domain

Configure the cookie domain and path:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your super base64url encoded secret key')
    ->withCookie('.mydomain.com', '/')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

## Configuration Methods Reference

### `withSecret(string $secret)`
Set the secret key for JWT encoding/decoding. The secret must be base64url encoded.

### `withRsaSecret(string $private, string $public)`
Use RSA private/public keys instead of a shared secret. See [RSA Keys](rsa-keys.md) for details.

### `withTimeoutMinutes(int $timeout)`
Set the JWT token validity in minutes. Default is 20 minutes.

### `withTimeoutHours(int $timeout)`
Set the JWT token validity in hours. Convenience method that converts hours to minutes internally.

### `withSessionContext(string $context)`
Set a custom session context name. Default is 'default'. This allows multiple independent sessions.

### `withCookie(string $domain, string $path = '/')`
Configure the cookie domain and path. The domain should include the leading dot for subdomain support (e.g., '.example.com').

### `replaceSessionHandler(bool $startSession = true)`
Automatically replace PHP's session handler and optionally start the session immediately.
