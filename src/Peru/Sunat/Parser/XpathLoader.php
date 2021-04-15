<?php

declare(strict_types=1);

namespace Peru\Sunat\Parser;

use DOMDocument;
use DOMXPath;

class XpathLoader
{
    public static function getXpathFromHtml(string $html): DOMXPath
    {
        $dom = new DOMDocument();
        $prevState = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($prevState);

        return new DOMXPath($dom);
    }
}
