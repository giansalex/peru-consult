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
use Peru\Http\ContextClient;
use Peru\Http\EmptyResponseDecorator;
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
    /**
     * @var Ruc
     */
    private $cs;

    public function setUp()
    {
        $client = new ContextClient();
        $client->options = [
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $this->cs = new Ruc();
        $this->cs->setClient(new ClientStubDecorator(new EmptyResponseDecorator($client)));
    }

    /**
     * @dataProvider rucProviders
     *
     * @param string $ruc
     * @throws Exception
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
        $this->assertTrue(new DateTime($company->fechaInscripcion) !== false);
        $this->assertNotEmpty($company->departamento);
        $this->assertNotEmpty($company->provincia);
        $this->assertNotEmpty($company->distrito);
    }

    public function testJsonEncode()
    {
        $company = $this->cs->get('10401510465');

        $this->assertNotNull($company);
        $json = json_encode($company);
        $this->assertJson($json);
        $obj = json_decode($json);
        $this->assertNotEmpty($obj->ruc);
    }

    public function testInvalidResponse()
    {
        $ruc = new Ruc();
        $ruc->setParser(new RucParser(new HtmlParser()));

        $company = $ruc->get('20000000001');

        $this->assertNull($company);
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
            ['20440374248'], // 20550263948LA LIBERTAD
            ['20550263948'],
            ['20493919271'], // MADRE DE DIOS
            ['20146806679'], // SAN MARTIN
        ];
    }
}
