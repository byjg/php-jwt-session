<?php

namespace ByJG\Session;

use ByJG\Util\JwtWrapper;
use SessionHandlerInterface;

class JwtSession implements SessionHandlerInterface
{
    const COOKIE_PREFIX = "AUTH_BEARER_";

    protected $serverName;

    protected $secretKey;

    protected $timeOutMinutes;

    protected $suffix = "default";

    protected $cookieDomain;

    /**
     * JwtSession constructor.
     *
     * @param $serverName
     * @param $secretKey
     * @param int $timeOutMinutes
     */
    public function __construct($serverName, $secretKey, $timeOutMinutes = null, $sessionContext = null, $cookieDomain = null)
    {
        $this->serverName = $serverName;
        $this->secretKey = $secretKey;
        $this->timeOutMinutes = $timeOutMinutes ?: 20;
        $this->suffix = $sessionContext ?: 'default';
        $this->cookieDomain = $cookieDomain;
    }

    public function replaceSessionHandler($startSession = true)
    {
        if (session_status() != PHP_SESSION_NONE) {
            throw new \Exception('Session already started!');
        }

        session_set_save_handler($this, true);

        if ($startSession) {
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
    public function close()
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($session_id)
    {
        if (!headers_sent()) {
            setcookie(self::COOKIE_PREFIX . $this->suffix, null);
        }

        return true;
    }

    /**
     * Cleanup old sessions
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * Initialize session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Read session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($session_id)
    {
        try {
            if (isset($_COOKIE[self::COOKIE_PREFIX . $this->suffix])) {
                $jwt = new JwtWrapper($this->serverName, $this->secretKey);
                $data = $jwt->extractData($_COOKIE[self::COOKIE_PREFIX . $this->suffix]);

                return $this->serializeSessionData($data->data);
            }
            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    /**
     * Write session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
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
     * @since 5.4.0
     */
    public function write($session_id, $session_data)
    {
        $jwt = new JwtWrapper($this->serverName, $this->secretKey);
        $data = $jwt->createJwtData($this->unSerializeSessionData($session_data), $this->timeOutMinutes * 60);
        $token = $jwt->generateToken($data);

        if (!headers_sent()) {
            setcookie(self::COOKIE_PREFIX . $this->suffix, $token, null, '/', $this->cookieDomain);
        }

        return true;
    }

    public function serializeSessionData($array)
    {
        $result = '';
        foreach ($array as $key => $value) {
            $result .= $key . "|" . serialize($value);
        }

        return $result;
    }

    public function unSerializeSessionData($session_data)
    {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|")) {
                throw new \Exception("invalid data, remaining: " . substr($session_data, $offset));
            }
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }

        return $return_data;
    }
}