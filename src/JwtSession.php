<?php

namespace ByJG\Session;

use ByJG\JwtWrapper\JwtWrapper;
use ByJG\JwtWrapper\JwtWrapperException;
use Exception;
use SessionHandlerInterface;

class JwtSession implements SessionHandlerInterface
{
    const COOKIE_PREFIX = "AUTH_BEARER_";

    /**
     * @var SessionConfig
     */
    protected SessionConfig $sessionConfig;

    /**
     * JwtSession constructor.
     *
     * @param $sessionConfig
     * @throws JwtSessionException
     */
    public function __construct($sessionConfig)
    {
        ini_set("session.use_cookies", 0);

        if (!($sessionConfig instanceof SessionConfig)) {
            throw new JwtSessionException('Required SessionConfig instance');
        }

        $this->sessionConfig = $sessionConfig;

        if ($this->sessionConfig->isReplaceSession()) {
            $this->replaceSessionHandler();
        }
    }

    /**
     * @throws JwtSessionException
     */
    protected function replaceSessionHandler(): void
    {
        if (session_status() != PHP_SESSION_NONE) {
            throw new JwtSessionException('Session already started!');
        }

        session_set_save_handler($this, true);

        if ($this->sessionConfig->isStartSession()) {
            ob_start();
            session_start();
        }
    }

    /**
     * Close the session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy(string $id): bool
    {
        if (!headers_sent()) {
            setcookie(
                self::COOKIE_PREFIX . $this->sessionConfig->getSessionContext(),
                "",
                (time()-3000),
                $this->sessionConfig->getCookiePath() ?? "",
                $this->sessionConfig->getCookieDomain() ?? "",
            );
        }

        return true;
    }

    /**
     * Cleanup old sessions
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $max_lifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return int|false <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc(int $max_lifetime): int|false
    {
        return true;
    }

    /**
     * Initialize session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }

    /**
     * Read session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read(string $id): string
    {
        try {
            if (isset($_COOKIE[self::COOKIE_PREFIX . $this->sessionConfig->getSessionContext()])) {
                $jwt = new JwtWrapper(
                    $this->sessionConfig->getServerName(),
                    $this->sessionConfig->getKey()
                );
                $data = $jwt->extractData($_COOKIE[self::COOKIE_PREFIX . $this->sessionConfig->getSessionContext()]);

                if (empty($data->data)) {
                    return '';
                }

                return $data->data;
            }
            return '';
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * Write session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $id The session id.
     * @param string $data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @throws JwtWrapperException
     * @since 5.4.0
     */
    public function write(string $id, string $data): bool
    {
        $jwt = new JwtWrapper(
            $this->sessionConfig->getServerName(),
            $this->sessionConfig->getKey()
        );
        $session_data = $jwt->createJwtData(['data' => $data], $this->sessionConfig->getTimeoutMinutes() * 60, 0, null);
        $token = $jwt->generateToken($session_data);

        if (!headers_sent()) {
            setcookie(
                self::COOKIE_PREFIX . $this->sessionConfig->getSessionContext(),
                $token,
                (time()+$this->sessionConfig->getTimeoutMinutes()*60) ,
                $this->sessionConfig->getCookiePath() ?? "",
                $this->sessionConfig->getCookieDomain() ?? "",
                false,
                true
            );
            if (defined("SETCOOKIE_FORTEST")) {
                $_COOKIE[self::COOKIE_PREFIX . $this->sessionConfig->getSessionContext()] = $token;
            }
        }

        return true;
    }

    public function serializeSessionData($array): string
    {
        $result = '';
        foreach ($array as $key => $value) {
            $result .= $key . "|" . serialize($value);
        }

        return $result;
    }

    /**
     * @param $session_data
     * @return array
     * @throws JwtSessionException
     */
    public function unSerializeSessionData($session_data): array
    {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            if (!str_contains(substr($session_data, $offset), "|")) throw new JwtSessionException("invalid data, remaining: " . substr($session_data, $offset));
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            $data = @unserialize(substr($session_data, $offset), ['allowed_classes' => true]);
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }

        return $return_data;
    }
}
