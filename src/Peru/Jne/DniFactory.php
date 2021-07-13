<?php

declare(strict_types=1);

namespace Peru\Jne;

use Peru\Http\CurlClient;
use Peru\Services\DniInterface;

class DniFactory
{
    public function create(): DniInterface
    {
        return new Dni(new CurlClient(), new DniParser());
    }
}