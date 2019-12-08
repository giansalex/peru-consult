<?php

declare(strict_types=1);

namespace Tests\Peru\Jne;

use Peru\Jne\DniParser;
use PHPUnit\Framework\TestCase;

class DniParserTest extends TestCase
{
    /**
     * @var DniParser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new DniParser();
    }

    /**
     * @param string $dni
     *
     * @testWith ["00000009"]
     *           ["00000003"]
     */
    public function testParseDni($dni)
    {
        $person = $this->parser->parse($dni, 'A|B|C');

        $this->assertNotNull($person);
        $this->assertNotNull($person->codVerifica);
    }
}
