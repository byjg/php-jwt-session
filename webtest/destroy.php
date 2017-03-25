<?php

require_once __DIR__ . "/../vendor/autoload.php";

$handler = new \ByJG\Session\JwtSession('api.com.br', '1234567890');
$handler->replaceSessionHandler(true);

session_destroy();
?>

<h1>JwtSession Demo - Destroy whole session</h1>

<div>
    Now, your session is empty again.
</div>

<div>
    Go back and check this page: <a href="index.php">Index</a>
</div>
