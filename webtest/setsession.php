<?php

use ByJG\Session\JwtSession;
use ByJG\Session\SessionConfig;

require_once __DIR__ . "/../vendor/autoload.php";

$sessionConfig = (new SessionConfig('api.com.br'))
    ->withSecret('c2VjcmV0S2V5X3RoaXNfaXNfYV92ZXJ5X2xvbmdfc2VjcmV0X2tleV90aGF0X2lzXzY0X2J5dGVzX2xvbmchIQ==')
    ->withSecureCookie(false)
    ->replaceSessionHandler();
$handler = new JwtSession($sessionConfig);

$count = intval($_SESSION['count'] ?? 0) + 1;

$_SESSION['count'] = $count;
$_SESSION['setvalue_' . $count] = 'Set at date ' . date('Y-m-d H:i:s');

?>

<h1>JwtSession Demo - Set Session</h1>

<div>
    Everytime you reach this page I'll create a new key: setvalue1, setvalue2, ...
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
