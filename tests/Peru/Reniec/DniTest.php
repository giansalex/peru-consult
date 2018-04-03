<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

namespace Tests\Peru\Reniec;

use Peru\Http\ContextClient;
use Peru\Reniec\Dni;

/**
 * Class DniTest
 * @package Tests\Peru\Reniec
 */
class DniTest extends \PHPUnit_Framework_TestCase
{
    use DniTrait {
        getHttpMock as private getHttp;
    }

    /**
     * @var Dni
     */
    private $cs;

    public function setUp()
    {
        $this->cs = new Dni();
        $this->cs->setClient(new ContextClient());
    }

    /**
     * @dataProvider dniProviders
     * @param string $dni
     */
    public function testGetDni($dni)
    {
        $person = $this->cs->get($dni);

        $this->assertNotFalse($person);
        $this->assertEquals($dni, $person->dni);
        $this->assertNotEmpty($person->nombres);
        $this->assertNotEmpty($person->apellidoMaterno);
        $this->assertNotEmpty($person->apellidoPaterno);
    }

    public function testInvalidRequest()
    {
        $dni = new Dni();
        $dni->setClient($this->getClientCaptchaMock(Dni::URL_CONSULT));

        $cs = $dni->get('00000001');

        $this->assertFalse($cs);
        $this->assertEquals('Ocurrio un problema conectando a Reniec', $dni->getError());
    }

    public function testInvalidCaptcha()
    {
        $dni = new Dni();
        $dni->setClient($this->getClientMock(null));

        $cs = $dni->get('00000001');

        $this->assertFalse($cs);
        $this->assertEquals('No se pudo crear imagen desde el captcha', $dni->getError());
    }

    public function testInvalidRequestCaptcha()
    {
        $dni = new Dni();
        $dni->setClient($this->getClientMock(Dni::URL_CAPTCHA));

        $cs = $dni->get('00000001');

        $this->assertFalse($cs);
        $this->assertEquals('No se pudo cargar el captcha image', $dni->getError());
    }

    public function testInvalidDniLength()
    {
        $person = $this->cs->get('2323');

        $this->assertFalse($person);
        $this->assertContains('8', $this->cs->getError());
    }

    public function testInvalidDni()
    {
        $person = $this->cs->get('00000000');

        $this->assertFalse($person);
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