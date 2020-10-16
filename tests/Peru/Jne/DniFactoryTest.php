<?php

declare(strict_types=1);

namespace Tests\Peru\Jne;

use Peru\Jne\Dni;
use Peru\Jne\DniFactory;
use PHPUnit\Framework\TestCase;

class DniFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new DniFactory();

        $cs = $factory->create();

        $this->assertInstanceOf(Dni::class, $cs);
    }
}