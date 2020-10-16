<?php

declare(strict_types=1);

namespace Tests\Peru\Sunat;

use Peru\Sunat\Ruc;
use Peru\Sunat\RucFactory;
use PHPUnit\Framework\TestCase;

class RucFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new RucFactory();

        $cs = $factory->create();

        $this->assertInstanceOf(Ruc::class, $cs);
    }
}