<?php

namespace Tests\Peru\Sunat\Async;

use function Clue\React\Block\await;
use Peru\Http\Async\HttpClient;
use Peru\Sunat\Async\Ruc;
use Peru\Sunat\Company;
use Peru\Sunat\HtmlParser;
use Peru\Sunat\RucParser;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

class RucTest extends TestCase
{
    /**
     * @throws \Exception when the promise is rejected
     */
    public function testGetRuc()
    {
        $loop = Factory::create();
        $cs = new Ruc(new HttpClient($loop), new RucParser(new HtmlParser()));
        $promise = $cs->get('10401510465');
        /**@var $company Company */
        $company = await($promise, $loop);

        $this->assertNotNull($company);
        $this->assertEquals('10401510465', $company->ruc);
        $this->assertNotEmpty($company->razonSocial);

        $loop->run();
    }
}
