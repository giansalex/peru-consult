<?php

declare(strict_types=1);

namespace Peru\Sunat;

final class Endpoints
{
    public const CONSULT = 'https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    public const RANDOM = 'https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';
    public const USER_VALIDEZ = 'https://ww3.sunat.gob.pe/cl-ti-itestadousr/usrS00Alias';
    public const RANDOM_PAGE = 'https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias?accion=consPorRazonSoc&razSoc=BVA%20FOODS';
}
