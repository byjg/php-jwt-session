<?php

use ByJG\JwtWrapper\JwtWrapperException;
use ByJG\Session\JwtSession;
use ByJG\Session\JwtSessionException;
use ByJG\Session\SessionConfig;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

ob_start();
define("SETCOOKIE_FORTEST", "TESTCASE");

class JwtSessionTest extends TestCase
{
    /**
     * @var ?JwtSession
     */
    protected ?JwtSession $object;

    /**
     * @var SessionConfig
     */
    protected SessionConfig $sessionConfig;

    const string SESSION_ID = "sessionid";

    /**
     * @throws JwtSessionException
     */
    protected function setUp(): void
    {
        $this->sessionConfig = (new SessionConfig('example.com'))
            ->withSecret('secretKey');

        $this->object = new JwtSession($this->sessionConfig);
    }

    protected function tearDown(): void
    {
        header_remove();
        $_COOKIE = [];
        $this->object = null;
    }


    public function testDestroy()
    {
        $this->assertTrue($this->object->destroy(self::SESSION_ID));
    }

    public function testGc()
    {
        $this->assertEquals(1, $this->object->gc(0));
    }

    public function testClose()
    {
        $this->assertTrue($this->object->close());
    }

    public static function dataProvider(): array
    {
        $obj = new stdClass();
        $obj->prop1 = "value1";
        $obj->prop2 = "value2";

        return
        [
            [
                [
                    "text" => "simple string"
                ],
                "text|s:13:\"simple string\";"
            ],
            [
                [
                    "text" => "simple string",
                    "text2" => "another string",
                    "number" => 74
                ],
                "text|s:13:\"simple string\";text2|s:14:\"another string\";number|i:74;"
            ],
            [
                [
                    "text" => [ 1, 2, 3 ]
                ],
                "text|a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}"
            ],
            [
                [
                    "text" => [ "a" => 1, "b" => 2, "c" => 3 ]
                ],
                "text|a:3:{s:1:\"a\";i:1;s:1:\"b\";i:2;s:1:\"c\";i:3;}"
            ],
            [
                [
                    "text" => [ "a" => 1, "b" => 2, "c" => 3 ],
                    "single" => 2000
                ],
                "text|a:3:{s:1:\"a\";i:1;s:1:\"b\";i:2;s:1:\"c\";i:3;}single|i:2000;"
            ],
            [
                [
                    "text" => $obj
                ],
                "text|O:8:\"stdClass\":2:{s:5:\"prop1\";s:6:\"value1\";s:5:\"prop2\";s:6:\"value2\";}"
            ],
            [
                [
                    "text" => [ "a" => $obj ]
                ],
                "text|a:1:{s:1:\"a\";O:8:\"stdClass\":2:{s:5:\"prop1\";s:6:\"value1\";s:5:\"prop2\";s:6:\"value2\";}}"
            ],
            [
                [
                    "text" => [ $obj ]
                ],
                "text|a:1:{i:0;O:8:\"stdClass\":2:{s:5:\"prop1\";s:6:\"value1\";s:5:\"prop2\";s:6:\"value2\";}}"
            ]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testSerializeSessionData($input, $expected)
    {
        $result = $this->object->serializeSessionData($input);
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('dataProvider')]
    public function testUnserializeData($expected, $input)
    {
        $result = $this->object->unSerializeSessionData($input);
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('dataProvider')]
    public function testReadWrite($object, $serialize)
    {
        $this->object->write("SESSID", $serialize);
        $result = $this->object->read("SESSID");
        $this->assertEquals($serialize, $result);
    }

}
