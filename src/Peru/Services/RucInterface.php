<?php

namespace Peru\Services;


use Peru\Sunat\Company;

interface RucInterface
{
    function get(string $ruc): ?Company;
}