<?php

namespace Tests\Peru\Jne\Async;

use function Clue\React\Block\await;
use Peru\Http\Async\HttpClient;
use Peru\Jne\Async\Dni;
use Peru\Jne\DniParser;
use Peru\Reniec\Person;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

class DniTest extends TestCase
{
    /**
     * @throws \Exception when the promise is rejected
     */
    public function testGetDni()
    {
        $loop = Factory::create();
        $cs = new Dni(new HttpClient($loop), new DniParser());
        $promise = $cs->get('48004836');
        /**@var $person Person */
        $person = await($promise, $loop);

        $this->assertNotNull($person);
        $this->assertEquals('48004836', $person->dni);

        $loop->run();
    }
}
