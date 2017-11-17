<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:21 PM
 */

namespace Tests\Peru\Reniec;

use Peru\Reniec\Dni;

/**
 * Class DniTest
 * @package Tests\Peru\Reniec
 */
class DniTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDni()
    {
        $myDni = '48004836';
        $cs = new Dni();
        $person = $cs->get($myDni);

        $this->assertNull($person);
//        $this->assertEquals($myDni, $person->dni);
//        $this->assertNotEmpty($person->primerNombre);
//        $this->assertNotEmpty($person->apellidoMaterno);
//        $this->assertNotEmpty($person->apellidoPaterno);
    }
}