<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/11/2017
 * Time: 19:59.
 */

declare(strict_types=1);

namespace Tests\Peru\Sunat;

use DateTime;
use Exception;
use Peru\Sunat\HtmlParser;
use Peru\Sunat\Ruc;
use Peru\Sunat\RucParser;
use PHPUnit\Framework\TestCase;

/**
 * Class RucTest
 * @package Tests\Peru\Sunat
 */
class RucTest extends TestCase
{
    private const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
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
    }

    /**
     * @dataProvider rucProviders
     *
     * @param string $ruc
     * @throws Exception
     */
    public function testGetRuc($ruc)
    {
        $company = $this->getRucRetry($ruc);
        if (!$company) return;

        $this->assertNotEmpty($company->ruc);
        $this->assertNotEmpty($company->razonSocial);
        $this->assertNotEmpty($company->estado);
        $this->assertNotEmpty($company->condicion);
        $this->assertNotEmpty($company->direccion);
        $this->assertNotEmpty($company->fechaInscripcion);
        $this->assertTrue(is_array($company->cpeElectronico));
        $this->assertTrue(new DateTime($company->fechaInscripcion) !== false);
        $this->assertNotEmpty($company->departamento);
        $this->assertNotEmpty($company->provincia);
        $this->assertNotEmpty($company->distrito);
    }

    public function testJsonEncode()
    {
        $company = $this->cs->get('10401510465');
        if (!$company) return;

        $this->assertNotNull($company);
        $json = json_encode($company);
        $this->assertJson($json);
        $obj = json_decode($json);
        $this->assertNotEmpty($obj->ruc);
    }

    public function testExtraDirection()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientHtmlMock());

        $cp = $ruc->get('20440374248');

        $this->assertNotNull($cp);
        $this->assertNull($cp->departamento);
        $this->assertNull($cp->provincia);
        $this->assertNull($cp->distrito);
    }

    public function testInvalidRequest()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientMock(self::URL_CONSULT));

        $cs = $ruc->get('20000000001');

        $this->assertNull($cs);
    }

    public function testInvalidResponse()
    {
        $ruc = new Ruc();
        $ruc->setClient($this->getClientMock(''));
        $ruc->setParser(new RucParser(new HtmlParser()));

        $cs = $ruc->get('20000000001');

        $this->assertNull($cs);
    }

    public function testInvalidRucLength()
    {
        $company = $this->cs->get('2323');

        $this->assertNull($company);
        $this->assertContains('11', $this->cs->getError());
    }

    public function testInvalidRuc()
    {
        $company = $this->cs->get('20000000001');

        $this->assertNull($company);
    }

    public function rucProviders()
    {
        return [
            ['20440374248'], // LA LIBERTAD
            ['20513176962'],
            ['20600055519'],
            ['20512530517'],
            ['20100070970'],
            ['20601197503'],
            ['20493919271'], // MADRE DE DIOS
            ['20146806679'], // SAN MARTIN
        ];
    }

    private function getRucRetry($ruc, $retry = 5)
    {
        while ($retry-->0) {
            $company = $this->cs->get($ruc);
            if ($company) {
                return $company;
            }
        }

        return null;
    }
}
