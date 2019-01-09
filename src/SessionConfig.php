<?php

namespace ByJG\Session;

use ByJG\Util\JwtKeyInterface;
use ByJG\Util\JwtKeySecret;
use ByJG\Util\JwtRsaKey;

class SessionConfig
{
    protected $serverName;

    protected $sessionContext = 'default';
    protected $timeoutMinutes = 20;
    protected $cookieDomain = null;
    protected $cookiePath = '/';
    protected $jwtKey = null;
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
        $this->jwtKey = new JwtKeySecret($secret);
        return $this;
    }
    
    public function withRsaSecret($private, $public) {
        $this->jwtKey = new JwtRsaKey($private, $public);
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
     * @return JwtKeyInterface
     */
    public function getKey()
    {
        return $this->jwtKey;
    }

    public function isReplaceSession() {
        return $this->replaceSessionHandler !== null;
    }

    public function isStartSession() {
        return $this->replaceSessionHandler === true;
    }
}
