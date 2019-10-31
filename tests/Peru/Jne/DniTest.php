<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

declare(strict_types=1);

namespace Tests\Peru\Jne;

use Peru\{Http\ClientInterface, Http\ContextClient, Http\EmptyResponseDecorator, Jne\Dni, Jne\DniParser};
use PHPUnit\Framework\TestCase;
use Tests\Peru\Sunat\ClientStubDecorator;

/**
 * Class DniTest
 * @package Tests\Peru\Reniec
 */
class DniTest extends TestCase
{
    /**
     * @var Dni
     */
    private $cs;

    public function setUp()
    {
        $client = new ContextClient();
        $client->options = [
            'http' => [
                'ignore_errors' => true,
            ]
        ];

        $this->cs = new Dni(new ClientStubDecorator(new EmptyResponseDecorator($client)), new DniParser());
    }

    /**
     * @param string $dni
     *
     * @testWith    ["48004836"]
     */
    public function testGetDni($dni)
    {
        $person = $this->cs->get($dni);

        $this->assertNotNull($person);
        $this->assertEquals($dni, $person->dni);
        $this->assertNotEmpty($person->nombres);
        $this->assertNotEmpty($person->apellidoMaterno);
        $this->assertNotEmpty($person->apellidoPaterno);

        $json = json_encode($person);
        $this->assertJson($json);
        $obj = json_decode($json);
        $this->assertNotEmpty($obj->dni);
    }

    public function testInvalidDni()
    {
        $person = $this->cs->get('00000000');

        $this->assertNull($person);
    }

    public function testServerEmptyResponse()
    {
        // arrange
        $stub = $this->getMockBuilder(ClientInterface::class)->getMock();
        $stub->method('get')->willReturn('');
        /**@var $stub ClientInterface */
        $client = new Dni($stub, new DniParser());

        // act
        $person = $client->get('0999');

        // assert
        $this->assertNull($person);
    }
}
