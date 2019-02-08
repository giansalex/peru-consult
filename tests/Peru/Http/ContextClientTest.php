<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 18/06/2018
 * Time: 22:07
 */

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
}