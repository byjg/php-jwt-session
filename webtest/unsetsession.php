<?php

use ByJG\Session\JwtSession;
use ByJG\Session\SessionConfig;

require_once __DIR__ . "/../vendor/autoload.php";

$sessionConfig = (new SessionConfig('api.com.br'))
    ->withSecret('c2VjcmV0S2V5X3RoaXNfaXNfYV92ZXJ5X2xvbmdfc2VjcmV0X2tleV90aGF0X2lzXzY0X2J5dGVzX2xvbmchIQ==')
    ->replaceSessionHandler();

$handler = new JwtSession($sessionConfig);

$count = intval($_SESSION['count'] ?? 0);

if ($count > 0) {
    unset($_SESSION['setvalue_' . $count]);
    $_SESSION['count'] = $count - 1;
}

?>

<h1>JwtSession Demo - Unset Session</h1>

<div>
    Everytime you reach this page I'll remove a session key: setvalue1, setvalue2, ...
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
