<?php

require_once __DIR__ . "/../vendor/autoload.php";

$sessionConfig = (new \ByJG\Session\SessionConfig('api.com.br'))
    ->withSecret('1234567890')
    ->replaceSessionHandler();

$handler = new \ByJG\Session\JwtSession($sessionConfig);

session_destroy();
?>

<h1>JwtSession Demo - Destroy whole session</h1>

<div>
    Now, your session is empty again.
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
