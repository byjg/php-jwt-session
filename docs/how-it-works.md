---
sidebar_position: 4
---

# How It Works

## Cookie Storage

JwtSession stores a cookie named `AUTH_BEARER_` followed by the context name with the session name.

The `PHPSESSID` cookie is still created because PHP creates it by default, but we do not use it.

## Architecture

The implementation follows PHP's `SessionHandlerInterface`, which requires implementing the following methods:

- **`open()`** - Initialize session
- **`close()`** - Close the session
- **`read()`** - Read session data from JWT cookie
- **`write()`** - Write session data to JWT cookie
- **`destroy()`** - Destroy a session by clearing the cookie
- **`gc()`** - Garbage collection (not used in JWT sessions as tokens are self-expiring)

## Session Flow

### Writing Session Data

1. PHP serializes the `$_SESSION` array
2. The `write()` method receives the serialized data
3. JwtWrapper creates a JWT token containing the session data
4. Token is set as a cookie with the configured expiration time
5. Cookie is sent to the client's browser

### Reading Session Data

1. Client sends the JWT cookie with the request
2. The `read()` method extracts the token from the cookie
3. JwtWrapper validates and decodes the JWT token
4. Session data is extracted and returned to PHP
5. PHP unserializes the data into the `$_SESSION` array

## Additional Methods

### `serializeSessionData(array $array): string`

Manually serialize session data into PHP session format. Each array entry is serialized as `key|serialized_value`.

### `unSerializeSessionData(string $session_data): array`

Parse PHP session serialized data back into an array. This method handles the custom session serialization format used internally by PHP.

## Cookie Configuration

Cookies are set with the following parameters:
- **Name**: `AUTH_BEARER_{context}`
- **Value**: JWT token containing encrypted session data
- **Expiration**: Current time + configured timeout in minutes
- **Path**: Configured cookie path (default: '/')
- **Domain**: Configured cookie domain
- **Secure**: false (can be enhanced in custom implementations)
- **HttpOnly**: true (prevents JavaScript access)
