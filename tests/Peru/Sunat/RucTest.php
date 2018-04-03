<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/11/2017
 * Time: 19:59.
 */

namespace Tests\Peru\Sunat;

use Peru\Http\ContextClient;
use Peru\Sunat\Ruc;

/**
 * Class RucTest
 * @package Tests\Peru\Sunat
 */
class RucTest extends \PHPUnit_Framework_TestCase
{
    use RucTrait {
        getHttpMock as private getHttp;
    }

    /**s
     * @var Ruc
     */
    private $cs;

    public function setUp()
    {
        $this->cs = new Ruc();
        $this->cs->setClient(new ContextClient());
    }

    /**
     * @dataProvider rucProviders
     *
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
        $this->assertTrue(is_array($company->cpeElectronico));
        $this->assertTrue(new \DateTime($company->fechaInscripcion) !== false);
        $this->assertNotEmpty($company->departamento);
        $this->assertNotEmpty($company->provincia);
        $this->assertNotEmpty($company->distrito);
//        file_put_contents($ruc.'.json', json_encode(get_object_vars($company), JSON_PRETTY_PRINT));
    }

    public function testExtraDirection()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientHtmlMock());

        $cp = $ruc->get('20440374248');

        $this->assertNotFalse($cp);
        $this->assertNull($cp->departamento);
        $this->assertNull($cp->provincia);
        $this->assertNull($cp->distrito);
    }

    public function testInvalidRequest()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientMock(Ruc::URL_CONSULT));

        $cs = $ruc->get('20000000001');

        $this->assertFalse($cs);
        $this->assertEquals('Ocurrio un problema conectando a Sunat', $ruc->getError());
    }

    public function testInvalidResponse()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientMock(''));

        $cs = $ruc->get('20000000001');

        $this->assertFalse($cs);
        $this->assertEquals('No se encontro el ruc', $ruc->getError());
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
            ['20440374248'], // LA LIBERTAD
            ['20513176962'],
//            ['10401510465'], // Direccion fiscal no disponible por SUNAT
            ['20600055519'],
            ['20512530517'],
            ['20100070970'],
            ['20601197503'],
            ['20493919271'], // MADRE DE DIOS
            ['20146806679'], // SAN MARTIN
        ];
    }
}
