<?php

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
     * @testWith ["000000009"]
     *           ["000000003"]
     */
    public function testParseDni($dni)
    {
        $person = $this->parser->parse($dni, 'A|B|C');

        $this->assertNotNull($person);
        $this->assertNotEmpty($person->codVerifica);
    }
}
