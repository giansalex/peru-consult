<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

declare(strict_types=1);

namespace Tests\Peru\Jne;

use Peru\{Http\ContextClient, Jne\Dni};
use PHPUnit\Framework\TestCase;

/**
 * Class DniTest
 * @package Tests\Peru\Reniec
 */
class DniTest extends TestCase
{
    use DniTrait;

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
    }

    /**
     * @dataProvider dniProviders
     * @param string $dni
     */
    public function testGetDni($dni)
    {
        $client = new Dni();
        $person = $client->get($dni);

        echo $this->cs->getError().PHP_EOL;
        $this->assertNotNull($person);
        $this->assertEquals($dni, $person->dni);
        $this->assertNotEmpty($person->nombres);
        $this->assertNotEmpty($person->apellidoMaterno);
        $this->assertNotEmpty($person->apellidoPaterno);
    }

    public function testInvalidRequest()
    {
        $dni = new Dni();
        $dni->setClient($this->getClientMock());

        $cs = $dni->get('00000001');

        $this->assertNull($cs);
        $this->assertEquals('No se pudo conectar a JNE', $dni->getError());
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

    public function dniProviders()
    {
        return [
            ['00000004'],
            ['00000012'],
            ['00000005'],
            ['00000023'],
            ['00000010'],
            ['48004836'],
        ];
    }
}