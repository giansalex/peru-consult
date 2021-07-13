<?php

declare(strict_types=1);

namespace Peru\Sunat;

use Peru\Http\CurlClient;
use Peru\Services\RucInterface;
use Peru\Sunat\Parser\HtmlRecaptchaParser;

class RucFactory
{
    public function create(): RucInterface
    {
        return new Ruc(new CurlClient(), new RucParser(new HtmlRecaptchaParser()));
    }
}
