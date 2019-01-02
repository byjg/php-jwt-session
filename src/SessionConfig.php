<?php

namespace ByJG\Session;

class SessionConfig
{
    protected $serverName;

    protected $sessionContext = 'default';
    protected $timeoutMinutes = 20;
    protected $cookieDomain = null;
    protected $cookiePath = '/';
    protected $secretKey = null;
    protected $publicKey = null;
    protected $replaceSessionHandler = null;

    /**
     * SessionConfig constructor.
     * @param $serverName
     */
    public function __construct($serverName)
    {
        $this->serverName = $serverName;
    }

    public function withSessionContext($context) {
        $this->sessionContext = $context;
        return $this;
    }

    public function withTimeoutMinutes($timeout) {
        $this->timeoutMinutes = $timeout;
        return $this;
    }

    public function withTimeoutHours($timeout) {
        $this->timeoutMinutes = $timeout * 60;
        return $this;
    }

    public function withCookie($domain, $path = "/") {
        $this->cookieDomain = $domain;
        $this->cookiePath = $path;
        return $this;
    }

    public function withSecret($secret) {
        $this->secretKey = $secret;
        $this->publicKey = null;
        return $this;
    }
    
    public function withRsaSecret($private, $public) {
        $this->secretKey = $private;
        $this->publicKey = $public;
        return $this;
    }

    public function replaceSessionHandler($startSession = true) {
        $this->replaceSessionHandler = $startSession;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @return string
     */
    public function getSessionContext()
    {
        return $this->sessionContext;
    }

    /**
     * @return int
     */
    public function getTimeoutMinutes()
    {
        return $this->timeoutMinutes;
    }

    /**
     * @return null
     */
    public function getCookieDomain()
    {
        return $this->cookieDomain;
    }

    /**
     * @return string
     */
    public function getCookiePath()
    {
        return $this->cookiePath;
    }

    /**
     * @return null
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @return null
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function isReplaceSession() {
        return $this->replaceSessionHandler !== null;
    }

    public function isStartSession() {
        return $this->replaceSessionHandler === true;
    }
}
