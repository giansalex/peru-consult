<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

declare(strict_types=1);

namespace Tests\Peru\Jne;

use Peru\{Http\ContextClient, Jne\Dni, Jne\DniParser};
use PHPUnit\Framework\TestCase;

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

        $this->cs = new Dni();
        $this->cs->setClient($client);
        $this->cs->setParser(new DniParser());
    }

    /**
     * @param string $dni
     *
     * @testWith    ["48004836"]
     */
    public function testGetDni($dni)
    {
        $client = new Dni();
        $person = $client->get($dni);

        $this->assertNotNull($person);
        $this->assertEquals($dni, $person->dni);
        $this->assertNotEmpty($person->nombres);
        $this->assertNotEmpty($person->apellidoMaterno);
        $this->assertNotEmpty($person->apellidoPaterno);
    }

    public function testInvalidDniLength()
    {
        $person = $this->cs->get('2323');

        $this->assertNull($person);
        $this->assertEquals('Dni debe tener 8 dígitos', $this->cs->getError());
    }

    public function testInvalidDni()
    {
        $person = $this->cs->get('00000000');

        $this->assertNull($person);
    }
}