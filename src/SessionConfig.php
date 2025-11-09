<?php

namespace ByJG\Session;

use ByJG\JwtWrapper\JwtKeyInterface;
use ByJG\JwtWrapper\JwtHashHmacSecret;
use ByJG\JwtWrapper\JwtOpenSSLKey;

class SessionConfig
{
    protected string $serverName;

    protected string $sessionContext = 'default';
    protected int $timeoutMinutes = 20;
    protected ?string $cookieDomain = null;
    protected string $cookiePath = '/';
    protected ?JwtKeyInterface $jwtKey = null;
    protected ?bool $replaceSessionHandler = null;

    /**
     * SessionConfig constructor.
     * @param $serverName
     */
    public function __construct($serverName)
    {
        $this->serverName = $serverName;
    }

    public function withSessionContext($context): static
    {
        $this->sessionContext = $context;
        return $this;
    }

    public function withTimeoutMinutes($timeout): static
    {
        $this->timeoutMinutes = $timeout;
        return $this;
    }

    public function withTimeoutHours($timeout): static
    {
        $this->timeoutMinutes = $timeout * 60;
        return $this;
    }

    public function withCookie($domain, $path = "/"): static
    {
        $this->cookieDomain = $domain;
        $this->cookiePath = $path;
        return $this;
    }

    public function withSecret($secret): static
    {
        $this->jwtKey = new JwtHashHmacSecret($secret);
        return $this;
    }

    public function withRsaSecret($private, $public): static
    {
        $this->jwtKey = new JwtOpenSSLKey($private, $public);
        return $this;
    }

    public function replaceSessionHandler($startSession = true): static
    {
        $this->replaceSessionHandler = $startSession;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerName(): string
    {
        return $this->serverName;
    }

    /**
     * @return string
     */
    public function getSessionContext(): string
    {
        return $this->sessionContext;
    }

    /**
     * @return int
     */
    public function getTimeoutMinutes(): int
    {
        return $this->timeoutMinutes;
    }

    /**
     * @return string|null
     */
    public function getCookieDomain(): ?string
    {
        return $this->cookieDomain;
    }

    /**
     * @return string
     */
    public function getCookiePath(): string
    {
        return $this->cookiePath;
    }

    /**
     * @return JwtKeyInterface|null
     */
    public function getKey(): ?JwtKeyInterface
    {
        return $this->jwtKey;
    }

    public function isReplaceSession(): bool
    {
        return $this->replaceSessionHandler !== null;
    }

    public function isStartSession(): bool
    {
        return $this->replaceSessionHandler === true;
    }
}
