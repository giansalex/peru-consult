<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/11/2017
 * Time: 19:59
 */

namespace Tests\Peru\Sunat;

use Peru\Sunat\Ruc;

class RucTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ruc
     */
    private $cs;

    public function setUp()
    {
        $this->cs = new Ruc();
    }

    /**
     * @dataProvider rucProviders
     * @param string $ruc
     */
    public function testGetRuc($ruc)
    {
        $company = $this->cs->get($ruc);

        $this->assertNotEmpty($company->ruc);
        $this->assertNotEmpty($company->razonSocial);
        $this->assertNotEmpty($company->estado);
        $this->assertNotEmpty($company->condicion);
        $this->assertNotEmpty($company->direccion);
        $this->assertNotEmpty($company->fechaInscripcion);
        $this->assertNotEmpty($company->fechaEmisorFe);
//        file_put_contents($ruc.'.json', json_encode(get_object_vars($company), JSON_PRETTY_PRINT));
    }

    public function testInvalidRucLength()
    {
        $company = $this->cs->get('2323');

        $this->assertFalse($company);
        $this->assertContains('11', $this->cs->getError());
    }

    public function testInvalidRuc()
    {
        $company = $this->cs->get('20000000001');

        $this->assertFalse($company);
        $this->assertEquals('No se encontro el ruc', $this->cs->getError());
    }

    public function rucProviders()
    {
        return [
          ['20513176962'],
          ['10480048356'],
          ['20600055519'],
          ['20512530517'],
          ['20100070970'],
          ['20601197503'],
        ];
    }
}