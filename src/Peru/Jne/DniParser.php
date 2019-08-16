<?php

namespace Peru\Jne;

use Peru\Reniec\Person;

class DniParser
{
    public function parse(string $raw): ?Person
    {
        $parts = explode('|', $raw);
        if (count($parts) < 3) {
            return null;
        }

        $person = new Person();
        $person->apellidoPaterno = $parts[0];
        $person->apellidoMaterno = $parts[1];
        $person->nombres = $parts[2];

        return $person;
    }
}
