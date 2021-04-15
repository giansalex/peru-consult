<?php

declare(strict_types=1);

namespace Peru\Sunat;

interface HtmlParserInterface
{
    /**
     * @param string $html
     * @return array|false
     */
    public function parse(string $html);
}
