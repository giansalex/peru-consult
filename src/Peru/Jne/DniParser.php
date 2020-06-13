<?php

namespace Peru\Jne;

use Peru\Reniec\Person;

class DniParser
{
    public function parse(string $dni, string $raw): ?Person
    {
        $parts = explode('|', $raw);
        if (count($parts) !== 3 || empty($parts[0])) {
            return null;
        }

        $person = new Person();
        $person->dni = $dni;
        $person->apellidoPaterno = $parts[0];
        $person->apellidoMaterno = $parts[1];
        $person->nombres = $parts[2];
        $person->codVerifica = strval($this->getVerifyCode($dni));

        return $person;
    }

    private function getVerifyCode($dni)
    {
        $suma = 5;
        $len = strlen($dni);
        $hash = [3, 2, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < $len; ++$i) {
            $suma += $dni[$i] * $hash[$i];
        }
        $entero = (int) ($suma / 11);
        $digito = 11 - ($suma - $entero * 11);

        return $digito > 9 ? $digito - 10 : $digito;
    }
}
