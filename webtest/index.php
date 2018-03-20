<?php

require_once __DIR__ . "/../vendor/autoload.php";

if (!isset($_REQUEST['turnoff'])) {   // Just for turnoff the session
    $handler = new \ByJG\Session\JwtSession('api.com.br', '1234567890');
    $handler->replaceSessionHandler(true);
} else {
    echo "<H1>JWT Session is disabled</H1>";
    session_start();
}

?>

<h1>JwtSession Demo</h1>

<div>
    Here the user just use the JwtSession as the session handler.
    The $_SESSION handler current is:
</div>

<div>
    <textarea cols="50" rows="20"><?php print_r($_SESSION);?>
    </textarea>
</div>

<div>
    Now, play with sessions:
    <ul>
        <li><a href="setsession.php">Set a session</a></li>
        <li><a href="unsetsession.php">Unset a session</a></li>
        <li><a href="destroy.php">Destroy all session</a></li>
        <li><a href="index.php">Refresh Page</a></li>
        <li><a href="index.php?turnoff=true">Turnoff JwtSession</a></li>
    </ul>
</div>
