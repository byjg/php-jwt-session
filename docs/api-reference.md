---
sidebar_position: 6
---

# API Reference

## Classes

### `ByJG\Session\JwtSession`

Implements PHP's `SessionHandlerInterface` to provide JWT-based session storage.

#### Constants

- **`COOKIE_PREFIX`** = `"AUTH_BEARER_"`
  Prefix for the session cookie name.

#### Constructor

```php
public function __construct(SessionConfig $sessionConfig)
```

**Parameters:**
- `$sessionConfig` (SessionConfig): Configuration object for the JWT session

**Throws:**
- `JwtSessionException`: If SessionConfig instance is invalid or session already started

#### SessionHandlerInterface Methods

##### `open(string $path, string $name): bool`

Initialize the session (no-op in JWT implementation).

##### `close(): bool`

Close the session (no-op in JWT implementation).

##### `read(string $id): string`

Read session data from JWT cookie.

**Parameters:**
- `$id` (string): Session ID (not used in JWT implementation)

**Returns:** Serialized session data string, or empty string if no session exists

##### `write(string $id, string $data): bool`

Write session data to JWT cookie.

**Parameters:**
- `$id` (string): Session ID (not used in JWT implementation)
- `$data` (string): Serialized session data from PHP

**Returns:** true on success

**Throws:**
- `JwtWrapperException`: If JWT token generation fails

##### `destroy(string $id): bool`

Destroy the session by clearing the JWT cookie.

**Parameters:**
- `$id` (string): Session ID to destroy

**Returns:** true on success

##### `gc(int $max_lifetime): int|false`

Garbage collection (no-op in JWT implementation as tokens are self-expiring).

**Parameters:**
- `$max_lifetime` (int): Maximum session lifetime

**Returns:** true

#### Public Helper Methods

##### `serializeSessionData(array $array): string`

Manually serialize session data into PHP session format.

**Parameters:**
- `$array` (array): Associative array of session data

**Returns:** Serialized string in PHP session format

##### `unSerializeSessionData(string $session_data): array`

Parse PHP session serialized data back into an array.

**Parameters:**
- `$session_data` (string): Serialized session data

**Returns:** Associative array of session variables

**Throws:**
- `JwtSessionException`: If session data format is invalid

---

### `ByJG\Session\SessionConfig`

Configuration class for JWT sessions with fluent interface.

#### Constructor

```php
public function __construct(string $serverName)
```

**Parameters:**
- `$serverName` (string): Server name/domain for JWT token

#### Configuration Methods

##### `withSecret(string $secret): static`

Set the secret key for JWT encoding/decoding.

**Parameters:**
- `$secret` (string): Base64url encoded secret key

**Returns:** Self for method chaining

##### `withRsaSecret(string $private, string $public): static`

Use RSA private/public keys for JWT encoding/decoding.

**Parameters:**
- `$private` (string): RSA private key (PEM format)
- `$public` (string): RSA public key (PEM format)

**Returns:** Self for method chaining

##### `withTimeoutMinutes(int $timeout): static`

Set JWT token validity in minutes.

**Parameters:**
- `$timeout` (int): Timeout in minutes (default: 20)

**Returns:** Self for method chaining

##### `withTimeoutHours(int $timeout): static`

Set JWT token validity in hours.

**Parameters:**
- `$timeout` (int): Timeout in hours

**Returns:** Self for method chaining

##### `withSessionContext(string $context): static`

Set custom session context name.

**Parameters:**
- `$context` (string): Context name (default: 'default')

**Returns:** Self for method chaining

##### `withCookie(string $domain, string $path = '/'): static`

Configure cookie domain and path.

**Parameters:**
- `$domain` (string): Cookie domain (e.g., '.example.com')
- `$path` (string): Cookie path (default: '/')

**Returns:** Self for method chaining

##### `replaceSessionHandler(bool $startSession = true): static`

Configure automatic session handler replacement.

**Parameters:**
- `$startSession` (bool): Whether to start session automatically (default: true)

**Returns:** Self for method chaining

#### Getter Methods

##### `getServerName(): string`

Get the configured server name.

##### `getSessionContext(): string`

Get the session context name.

##### `getTimeoutMinutes(): int`

Get the timeout in minutes.

##### `getCookieDomain(): ?string`

Get the cookie domain.

##### `getCookiePath(): string`

Get the cookie path.

##### `getKey(): ?JwtKeyInterface`

Get the JWT key interface instance.

##### `isReplaceSession(): bool`

Check if session handler replacement is configured.

##### `isStartSession(): bool`

Check if automatic session start is enabled.

---

### `ByJG\Session\JwtSessionException`

Exception class for JWT session errors.

**Extends:** `Exception`

**Use cases:**
- Invalid SessionConfig provided
- Session already started
- Invalid serialized session data

## Example Usage

### Basic Implementation

```php
<?php
use ByJG\Session\SessionConfig;
use ByJG\Session\JwtSession;

try {
    $config = (new SessionConfig('example.com'))
        ->withSecret('your-base64url-encoded-secret')
        ->withTimeoutMinutes(30)
        ->withCookie('.example.com', '/');

    $handler = new JwtSession($config);
    session_set_save_handler($handler, true);
    session_start();

    // Use $_SESSION as normal
    $_SESSION['user_id'] = 123;

} catch (JwtSessionException $e) {
    // Handle exception
    error_log('Session error: ' . $e->getMessage());
}
```

### With Automatic Handler Replacement

```php
<?php
$config = (new SessionConfig('example.com'))
    ->withSecret('your-base64url-encoded-secret')
    ->replaceSessionHandler(true); // Automatically starts session

$handler = new JwtSession($config);

// Session is already started, use $_SESSION directly
$_SESSION['user_id'] = 123;
```
