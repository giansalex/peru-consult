<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

namespace Tests\Peru\Reniec;

use Peru\Http\ClientInterface;
use Peru\Reniec\Dni;

/**
 * Class DniTest
 * @package Tests\Peru\Reniec
 */
class DniTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Dni
     */
    private $cs;

    public function setUp()
    {
        $this->cs = new Dni();
    }

    /**
     * @dataProvider dniProviders
     * @param string $dni
     */
    public function testGetDni($dni)
    {
        $person = $this->cs->get($dni);

        if ($person == false) {
            echo 'Error DNI ' . $dni . ' -> ' . $this->cs->getError() . PHP_EOL;
        }

        $this->assertNotFalse($person);
        $this->assertEquals($dni, $person->dni);
        $this->assertNotEmpty($person->nombres);
        $this->assertNotEmpty($person->apellidoMaterno);
        $this->assertNotEmpty($person->apellidoPaterno);
    }

    public function testInvalidRequest()
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
//        $this->assertEquals('No se encontro resultados para el dni', $this->cs->getError());
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

    /**
     * @param $url
     * @return ClientInterface
     */
    private function getClientMock($url)
    {
        $stub = $this->getMockBuilder(ClientInterface::class)
            ->getMock();

        $stub->method('get')
            ->willReturnCallback(function ($param) use ($url) {
                if (empty($url)) {
                    return '111';
                }
                $count = strlen($url);
                if (substr($param, 0, $count) == $url) {
                    return false;
                }

                return '111';
            });

        /**@var $stub ClientInterface*/
        return $stub;
    }
}