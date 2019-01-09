<?php

use ByJG\Session\JwtSession;

require_once __DIR__ . "/JwtSessionTest.php";

class JwtSessionRsaTest extends JwtSessionTest
{
    protected function setUp()
    {
        $secret = <<<PRIVATE
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA5PMdWRa+rUJmg6QMNAPIXa+BJVN7W0vxPN3WTK/OIv5gxgmj
2inHGGc6f90TW/to948LnqGtcD3CD9KsI55MubafwBYjcds1o9opZ0vYwwdIV80c
OVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNxcRK38tOCApjZySx/NzMDeaXuWe+1
nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIklNnyq4TfAUSwl+KN/zjj3CXad1oDT
7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLnJW1WcLlAAIaAfABtSZboznsStMnY
to01wVknXKyERFs7FLHYqKQANIvRhFTptsehowIDAQABAoIBAEkJkaQ5EE0fcKqw
K8BwMHxKn81zi1e9q1C6iEHgl8csFV03+BCB4WTUkaH2udVPJ9ZJyPArLbQvz3fS
wl1+g4V/UAksRtRslPkXgLvWQ2k8KoTwBv/3nn9Kkozk/h8chHuii0BDs30yzSn4
SdDAc9EZopsRhFklv9xgmJjYalRk02OLck73G+d6MpDqX56o2UA/lf6i9MV19KWP
HYip7CAN+i6k8gA0KPHwr76ehgQ6YHtSntkWS8RfVI8fLUB1UlT3HmLgUBNXMWkQ
ZZbvXtNOt6NtW/WIAHEYeE9jmFgrpW5jKJSLn5iGVPFZwJIZXRPyELEs9NHWkS6e
GmdzxnECgYEA8+m05B/tmeZOuMrPVJV9g+aBDcuxmW+sdLRch+ccSmx4ZNQOLVoU
klYgTZq/a1O4ENq0h2WgccNlRHdcH4sXMBvLalA/tFhZMUuA/KXWyZ1F0hBnjHVF
cj1alHCqh+9qJDGdn4mxSmrp8p0rfeWgBwlFtJEJmjjDWDCtVY+JZcsCgYEA8EuV
WF/ilgDjgC4jMCYNuO0oFGBbtNP17PuU3kh8W+joqK/nufZ3NLy1WrDIpqa9YPex
328Nnjljf5GJWSdMchAp82waLzl7FaaBTY0iyFAK4J0jfC/fVLx82+wpM3utDnh8
9x5iIboO5U7uEJ7k8X2p64GoprlKJSRmGAJ7eIkCgYEAw5IsXI3NMY0cqcbUHvoO
PehgqfMdX+3O1XSYjM+eO35lulLdWzfTLtKn7BGcUi46dCkofzfZQd5uIEukLhaU
bRqcK45UxgHg4kmsDufaJKZaCWjl3hVZrZPMQSFlWsF41bSCshzxbr3y/3lOGhA4
E+w3W+S/Uk0ZNGkzUltYy6kCgYEA0gRNeBr9z7rhG4O3j3qC3dCxCfYZ0Na8hy5v
M0PJJQ9QYTa04iyOjVItcyE1jaoHtLtoA+9syJBB7RoHIBufzcVg1Pbzf7jOYeLP
+jbTYp3Kk/vjKsQwfj/rJM+oRu3eF9qo5dbxT6btI++zVGV7lbEOFN6Sx30EV6gT
bwKkZXkCgYEAnEtN43xL8bRFybMc1ZJErjc0VocnoQxCHm7LuAtLOEUw6CwwFj9Q
GOl+GViVuDHUNQvURLn+6gg4tAemYlob912xIPaU44+lZzTMHBOJBGMJKi8WogKi
V5+cz9l31uuAgNfjL63jZPaAzKs8Zx6R3O5RuezympwijCIGWILbO2Q=
-----END RSA PRIVATE KEY-----
PRIVATE;

        $public = <<<PUBLIC
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5PMdWRa+rUJmg6QMNAPI
Xa+BJVN7W0vxPN3WTK/OIv5gxgmj2inHGGc6f90TW/to948LnqGtcD3CD9KsI55M
ubafwBYjcds1o9opZ0vYwwdIV80cOVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNx
cRK38tOCApjZySx/NzMDeaXuWe+1nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIkl
Nnyq4TfAUSwl+KN/zjj3CXad1oDT7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLn
JW1WcLlAAIaAfABtSZboznsStMnYto01wVknXKyERFs7FLHYqKQANIvRhFTptseh
owIDAQAB
-----END PUBLIC KEY-----
PUBLIC;

        $this->sessionConfig = (new \ByJG\Session\SessionConfig('example.com'))
            ->withRsaSecret($secret, $public);

        $this->object = new JwtSession($this->sessionConfig);
    }
}
