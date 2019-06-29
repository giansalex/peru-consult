<?php

namespace Peru\Services;

use Peru\Reniec\Person;

interface DniInterface
{
    public function get(string $dni): ?Person;
}
