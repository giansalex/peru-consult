<?php

declare(strict_types=1);

namespace Peru\Sunat;

use Peru\Http\ContextClient;
use Peru\Services\RucInterface;

class RucFactory
{
    public function create(): RucInterface
    {
        return new Ruc(new ContextClient(), new RucParser(new HtmlParser()));
    }
}
