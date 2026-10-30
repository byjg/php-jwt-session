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
    ->withSecret('your base64 encoded 64-byte secret key')
    ->withTimeoutMinutes(60);   // You can use withTimeoutHours(1)

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

## Setting Different Session Contexts

You can create multiple independent session contexts:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your base64 encoded 64-byte secret key')
    ->withSessionContext('MYCONTEXT');

$handler = new \ByJG\Session\JwtSession($sessionConfig);
session_set_save_handler($handler, true);
```

## Replace Session Handler Automatically

You can automatically replace the session handler and start the session:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your base64 encoded 64-byte secret key')
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
    ->withSecret('your base64 encoded 64-byte secret key')
    ->withCookie('.mydomain.com', '/')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);
```

## Cookie Security

The session cookie is sent with `Secure`, `HttpOnly` and `SameSite=Lax` by default.

Browsers do not return a `Secure` cookie over plain HTTP, so sessions stop working on a
site that is not served over HTTPS. Turn it off only for local development:

```php
<?php
$sessionConfig = (new \ByJG\Session\SessionConfig('your.domain.com'))
    ->withSecret('your base64 encoded 64-byte secret key')
    ->withSecureCookie(false)
    ->replaceSessionHandler();
```

Use `withSameSite()` to change the `SameSite` attribute:

```php
->withSameSite('Strict')
```

## Configuration Methods Reference

### `withSecret(string $secret)`
Set the secret key for JWT encoding/decoding.

The secret must be **base64 encoded** (standard alphabet) and decode to **at least 64 bytes**:
the token is signed with HS512, and a shorter key fails with `Provided key is too short` when the
session is written. Create one with:

```bash
php -r 'echo base64_encode(random_bytes(64)), PHP_EOL;'
```

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

### `withSecureCookie(bool $secure = true)`
Set the `Secure` attribute of the cookie. Default is `true`.

### `withSameSite(string $sameSite)`
Set the `SameSite` attribute of the cookie: `Strict`, `Lax` or `None`. Default is `Lax`. Throws `JwtSessionException` for any other value. Browsers reject `None` without `Secure`.

### `replaceSessionHandler(bool $startSession = true)`
Automatically replace PHP's session handler and optionally start the session immediately.
