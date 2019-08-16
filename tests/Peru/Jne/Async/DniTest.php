<?php

namespace Tests\Peru\Jne\Async;

use Peru\Http\Async\HttpClient;
use Peru\Jne\Async\Dni;
use Peru\Jne\DniParser;
use Peru\Reniec\Person;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

class DniTest extends TestCase
{
    public function testGetDni()
    {
        $loop = Factory::create();
        $cs = new Dni(new HttpClient($loop), new DniParser());
        $promise = $cs->get('48004836');
        $promise->then(function (?Person $person) {
            $this->assertNull($person);
            $this->assertEquals('48004836', $person->dni);
        }, function ($e) {
            $this->fail($e);
        });

        $loop->run();
    }
}
