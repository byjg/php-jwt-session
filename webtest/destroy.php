<?php

use ByJG\Session\JwtSession;
use ByJG\Session\SessionConfig;

require_once __DIR__ . "/../vendor/autoload.php";

$sessionConfig = (new SessionConfig('api.com.br'))
    ->withSecret('c2VjcmV0S2V5X3RoaXNfaXNfYV92ZXJ5X2xvbmdfc2VjcmV0X2tleV90aGF0X2lzXzY0X2J5dGVzX2xvbmchIQ==')
    ->withSecureCookie(false)
    ->replaceSessionHandler();

$handler = new JwtSession($sessionConfig);

session_destroy();
?>

<h1>JwtSession Demo - Destroy whole session</h1>

<div>
    Now, your session is empty again.
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
