<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 18/06/2018
 * Time: 22:07
 */

declare(strict_types=1);

namespace Tests\Peru\Http;

use Peru\Http\ContextClient;
use PHPUnit\Framework\TestCase;

class ContextClientTest extends TestCase
{
    public function testGet()
    {
        $client = new ContextClient();
        $result = $client->get('http://httpbin.org/get?value=1');

        $obj = json_decode($result);

        $this->assertTrue(isset($obj->args->value));
    }

    public function testPost()
    {
        $client = new ContextClient();
        $result = $client->post('http://httpbin.org/post', [
            'value' => 1,
        ]);

        $obj = json_decode($result);

        $this->assertTrue(isset($obj->form->value));
    }

    public function testNotFoundUrl()
    {
        $client = new ContextClient();
        $result = $client->get('http://httpbin.org/get33');
        $error = error_get_last();

        $this->assertFalse($result);
        $this->assertStringContainsString('404 NOT FOUND', strtoupper($error['message']));
    }

    public function testNotResolveDomain()
    {
        $client = new ContextClient();
        $result = $client->get('http://http323bin.org');
        $error = error_get_last();

        $this->assertFalse($result);
        $this->assertStringContainsString('php_network_getaddresses', $error['message']);
    }
}
