<?php

namespace Tests\Peru\Sunat\Async;

use Peru\Http\Async\HttpClient;
use Peru\Sunat\Async\Ruc;
use Peru\Sunat\Company;
use Peru\Sunat\HtmlParser;
use Peru\Sunat\RucParser;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

class RucTest extends TestCase
{
    public function testGetRuc()
    {
        $loop = Factory::create();
        $cs = new Ruc(new HttpClient($loop), new RucParser(new HtmlParser()));
        $promise = $cs->get('10401510465');
        $promise->then(function (?Company $company) {
            $this->assertNotNull($company);
            $this->assertEquals('10401510465', $company->ruc);
        }, function ($e) {
            $this->fail($e);
        });

        $loop->run();
    }
}
