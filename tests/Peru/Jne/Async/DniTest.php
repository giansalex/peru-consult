<?php

declare(strict_types=1);

namespace Tests\Peru\Jne\Async;

use function Clue\React\Block\await;
use Peru\Http\Async\HttpClient;
use Peru\Jne\Async\Dni;
use Peru\Jne\DniParser;
use Peru\Reniec\Person;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Tests\Peru\Sunat\Async\HttpClientStub;

class DniTest extends TestCase
{
    /**
     * @var LoopInterface
     */
    private $loop;
    /**
     * @var Dni
     */
    private $consult;

    protected function setUp()
    {
        $this->loop = Factory::create();
        $this->consult = new Dni(new HttpClientStub(new HttpClient($this->loop)), new DniParser());
    }

    /**
     * @throws \Exception when the promise is rejected
     */
    public function testGetDni()
    {
         $promise = $this->consult->get('48004836');
         /**@var $person Person */
         $person = await($promise, $this->loop);

         $this->assertNotNull($person);
         $this->assertEquals('48004836', $person->dni);
    }

    protected function tearDown()
    {
        $this->loop->run();
    }
}
