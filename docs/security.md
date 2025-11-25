---
sidebar_position: 5
---

# Security Information

## Important Security Considerations

### JWT Tokens Can Be Read

The JWT Token cannot be changed, but **it can be read**.

This implementation saves the JWT into a client cookie. Because of this, **do not store sensible data like passwords in the session**.

:::danger
Never store sensitive information such as passwords, API keys, or personal identifiable information (PII) in the JWT session. The token is encoded but not encrypted when using shared secrets.
:::

## Best Practices

### 1. Use HTTPS

Always use HTTPS in production to prevent token interception during transmission.

### 2. Secret Key Management

- **Never commit** secret keys to version control
- Use **environment variables** to store secret keys
- Ensure secret keys are **base64url encoded**
- Rotate secret keys periodically

### 3. Token Timeout

Set appropriate timeout values based on your application's security requirements:

```php
// For high-security applications
->withTimeoutMinutes(15)

// For standard applications
->withTimeoutMinutes(60)

// For low-security or development environments
->withTimeoutHours(24)
```

### 4. Cookie Security

When configuring cookies:
- Use specific domain paths to limit cookie scope
- Consider the domain scope carefully (`.example.com` vs `example.com`)

### 5. Consider RSA Keys for Enhanced Security

For applications requiring higher security, use RSA private/public keys instead of shared secrets:

```php
->withRsaSecret($privateKey, $publicKey)
```

This provides:
- Asymmetric encryption
- Better key distribution security
- Enhanced protection against key compromise

## What JWT Sessions Protect Against

- **Session Fixation**: New token generated on each write
- **Server-side Storage Issues**: No server-side session storage required
- **Scaling Issues**: Works seamlessly across multiple servers
- **Token Tampering**: JWT signature prevents token modification

## What JWT Sessions Don't Protect Against

- **Token Theft**: If an attacker obtains the cookie, they can use it
- **XSS Attacks**: Store only non-sensitive data in sessions
- **Man-in-the-Middle**: Always use HTTPS
- **Token Content Privacy**: Token payload is readable (use RSA for better protection)

## Exception Handling

The library throws `JwtSessionException` in the following cases:
- Invalid SessionConfig instance provided
- Session already started when trying to replace handler
- Invalid serialized session data format

Always implement proper exception handling in production code.
