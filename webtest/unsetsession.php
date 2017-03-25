<?php

require_once __DIR__ . "/../vendor/autoload.php";

$handler = new \ByJG\Session\JwtSession('api.com.br', '1234567890');
$handler->replaceSessionHandler(true);

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
