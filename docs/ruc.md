# RUC

Consulta de RUC.
> Fuente: **SUNAT**.

## Requerimientos
- Tener cargada la extension `dom`.
- Tener activo [allow_url_fopen](https://www.php.net/manual/es/filesystem.configuration.php#ini.allow-url-fopen).

## Ejemplo

```php
use Peru\Sunat\RucFactory;

require 'vendor/autoload.php';

$ruc = '20100070970';

$factory = new RucFactory();
$cs = $factory->create();

$company = $cs->get($ruc);
if (!$company) {
    echo 'Not found';
    return;
}

echo json_encode($company);

```

!!! tip "Cambiar URL utilizada"
    Si necesita cambiar la url utilizada internamente para obtener la información del RUC, este es un ejemplo:  
    `$cs->urlConsult='http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS03Alias';`

## Resultado

Resultado en formato json.

```json
{
  "ruc": "20100070970",
  "razonSocial": "SUPERMERCADOS PERUANOS SOCIEDAD ANONIMA 'O ' S.P.S.A.",
  "nombreComercial": "-",
  "tipo": "SOCIEDAD ANONIMA",
  "estado": "ACTIVO",
  "condicion": "HABIDO",
  "direccion": "CAL.MORELLI NRO. 181 INT. P-2",
  "departamento": "LIMA",
  "provincia": "LIMA",
  "distrito": "SAN BORJA",
  "fechaInscripcion": "1992-10-09T00:00:00.000Z",
  "sistEmsion": "MECANIZADO",
  "sistContabilidad": "COMPUTARIZADO",
  "actExterior": "SIN ACTIVIDAD",
  "actEconomicas": [
    "Principal    - 4711 - VENTA AL POR MENOR EN COMERCIOS NO ESPECIALIZADOS CON PREDOMINIO DE LA VENTA DE ALIMENTOS, BEBIDAS O TABACO",
    "Secundaria 1 - 4530  - VENTA DE PARTES, PIEZAS Y ACCESORIOS PARA VEH\u00cdCULOS AUTOMOTORES",
    "Secundaria 2 - 5610 - ACTIVIDADES DE RESTAURANTES Y DE SERVICIO M\u00d3VIL DE COMIDAS"
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
    "DESDE LOS SISTEMAS DEL CONTRIBUYENTE. AUTORIZ DESDE 01\/01\/2013"
  ],
  "fechaEmisorFe": "2013-01-01T00:00:00.000Z",
  "cpeElectronico": [
    "FACTURA (desde 01/01/2013)",
    "BOLETA (desde 01/01/2013)"
  ],
  "fechaPle": "2011-09-08T00:00:00.000Z",
  "padrones": [
    "Incorporado al Régimen de Agentes de Retención de IGV (R.S.037-2002) a partir del 01\/06\/2002"
  ],
  "fechaBaja": null,
  "profesion": ""
}
```
