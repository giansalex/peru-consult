<?php

declare(strict_types=1);

namespace Peru\Sunat;

trait RandomTrait
{
    private static $Pattern = '/<input type="hidden" name="numRnd" value="(.*)">/';

    private function getRandom(string $html): string
    {
        preg_match_all(self::$Pattern, $html, $matches, PREG_SET_ORDER);

        return count($matches) > 0 ? $matches[0][1] : '';
    }
}
