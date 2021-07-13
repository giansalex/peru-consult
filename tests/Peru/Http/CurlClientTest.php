<?php declare(strict_types=1);

namespace Peru\Http;

use PHPUnit\Framework\TestCase;

class CurlClientTest extends TestCase
{
    public function testGet()
    {
        $client = new CurlClient();
        $result = $client->get('http://httpbin.org/get?value=1');

        $this->assertNotFalse($result);
        $obj = json_decode($result);

        $this->assertTrue(isset($obj->args->value));
    }

    public function testPost()
    {
        $client = new CurlClient();
        $result = $client->post('http://httpbin.org/post', [
            'value' => 1,
        ], ['Accept' => 'application/json']);

        $this->assertNotFalse($result);
        $obj = json_decode($result);

        $this->assertTrue(isset($obj->form->value));
    }
}