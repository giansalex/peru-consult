<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/01/2018
 * Time: 17:17
 */

declare(strict_types=1);

namespace Tests\Peru\Reniec;

use Peru\Reniec\CaptchaCodes;
use PHPUnit\Framework\TestCase;

class CatpchaCodesTest extends TestCase
{
    /**
     * @var CaptchaCodes
     */
    private $codes;

    protected function setUp()
    {
        $this->codes = new CaptchaCodes();
    }

    public function testGetLetter()
    {
        $data = '00111111111111111000010010011111111111111100001001000000100000111110000100100000010000111110000010010000001000111110000001001000000100011111000000100100000010011111100000010010000001011111010000001001000000101111101000000100100000011111100100000010010000001111100010000001001000001111100001000000100100000111110000100000010010000111110000010000001001000111110000001000000100100011111000000100000010010011111000000010000001001011111100000001000000100101111111111111111000010010111111111111111100001001';
        $letter = $this->codes->getLetter($data, 2);

        $this->assertEquals('Z', $letter);
    }

    public function testNotFoundLetter()
    {
        $data = '23231';
        $letter = $this->codes->getLetter($data, 1);

        $this->assertNull($letter);
    }
}