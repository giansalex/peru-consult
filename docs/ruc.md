# RUC

Consulta de RUC.
> Fuente: **SUNAT**.

Requerimientos
---------------
- Tener cargada la extension `dom`.

Ejemplo
--------

```php
use Peru\Sunat\Ruc;

require 'vendor/autoload.php';

$ruc = '20100070970';

$cs = new Ruc();

$company = $cs->get($ruc);
if (empty($company)) {
    echo $cs->getError();
    exit();
}

echo json_encode($company);

```

Resultado
---------

Resultado en formato json.

```json
{
    "ruc": "20100070970",
    "razonSocial": "SUPERMERCADOS PERUANOS SOCIEDAD ANONIMA 'O ' S.P.S.A.",
    "nombreComercial": "-",
    "telefonos": [
        "6188000",
        "993548438"
    ],
    "tipo": "SOCIEDAD ANONIMA",
    "estado": "ACTIVO",
    "condicion": "HABIDO",
    "direccion": "CAL.MORELLI NRO. 181 INT.",
    "departamento": "LIMA",
    "provincia": "LIMA",
    "distrito": "SAN BORJA",
    "fechaInscripcion": "1992-10-09T00:00:00.000Z",
    "sistEmsion": "MECANIZADO",
    "sistContabilidad": "COMPUTARIZADO",
    "actExterior": "SIN ACTIVIDAD",
    "actEconomicas": [
        "4711 - VENTA AL POR MENOR EN COMERCIOS NO ESPECIALIZADOS CON PREDOMINIO DE LA VENTA DE ALIMENTOS, BEBIDAS O TABACO",
        "50304 - VENTA PARTES, PIEZAS, ACCESORIOS.",
        "4530 - VENTA DE PARTES, PIEZAS Y ACCESORIOS PARA VEHÍCULOS AUTOMOTORES"
    ],
    "cpPago": [
        "FACTURA",
        "BOLETA DE VENTA",
        "LIQUIDACION DE COMPRA",
        "NOTA DE CREDITO",
        "NOTA DE DEBITO",
        "GUIA DE REMISION - REMITENTE",
        "COMPROBANTE DE RETENCION"
    ],
    "sistElectronica": [
        "DESDE LOS SISTEMAS DEL CONTRIBUYENTE. AUTORIZ DESDE 01/01/2013"
    ],
    "fechaEmisorFe": "2013-01-01T00:00:00.000Z",
    "cpeElectronico": [
        "FACTURA (desde 01/01/2013)",
        "BOLETA (desde 01/01/2013)"
    ],
    "fechaPle": "2011-09-08T00:00:00.000Z",
    "padrones": [
        "Incorporado al Régimen de Agentes de Retención de IGV (R.S.037-2002) a partir del 01/06/2002"
    ]
}
```