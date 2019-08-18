<?php

declare(strict_types=1);

namespace Tests\Peru\Http;

use Peru\Http\ClientInterface;
use Peru\Http\EmptyResponseDecorator;
use PHPUnit\Framework\TestCase;

class EmptyResponseDecoratorTest extends TestCase
{
    /**
     * @var EmptyResponseDecorator
     */
    private $client;
    protected function setUp()
    {
        $this->client = new EmptyResponseDecorator($this->createClient());
    }

    public function testGet()
    {
        $result = $this->client->get('https://github.com');

        $this->assertTrue(is_string($result));
    }

    public function testPost()
    {
        $result = $this->client->post('https://github.com', null);

        $this->assertTrue(is_string($result));
    }

    private function createClient()
    {
        $mock = $this->getMockBuilder(ClientInterface::class)->getMock();

        $mock->method('get')->willReturn(false);
        $mock->method('post')->willReturn(false);

        /**@var $mock ClientInterface*/
        return $mock;
    }
}