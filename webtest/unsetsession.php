<?php

require_once __DIR__ . "/../vendor/autoload.php";

$sessionConfig = (new \ByJG\Session\SessionConfig('api.com.br'))
    ->withSecret('1234567890')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);

$count = intval($_SESSION['count']);

unset($_SESSION['setvalue_' . $count]);
$_SESSION['count'] = $count - 1;

?>

<h1>JwtSession Demo - Set Session</h1>

<div>
    Everytime you reach this page I'll remove a session key: setvalue1, setvalue2, ...
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
