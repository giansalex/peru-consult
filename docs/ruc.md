# RUC

Consulta del RUC usando el servicio público de Sunat.

Requerimientos
---------------
- Tener cargada la extension `dom`.

Ejemplo
--------

```php
use Peru\Sunat\Ruc;

require 'vendor/autoload.php';

$ruc = '20513176962';

$cs = new Ruc();
$company = $cs->get($ruc);
if ($company === false) {
    echo $cs->getError();
    exit();
}

echo json_encode(get_object_vars($company));

```

Resultado
---------

Resultado en formato json.

```json
{
   "ruc":"20513176962",
   "razonSocial":"ABLIMATEX EXPORT S.A.C.",
   "nombreComercial":"-",
   "telefonos":[
      "4127420",
      "997501515",
      "997501513"
   ],
   "tipo":"SOCIEDAD ANONIMA CERRADA",
   "estado":"ACTIVO",
   "condicion":"HABIDO",
   "direccion":"JR. ITALIA NRO. 1404 INT. 4-A (4TO. PISO)",
   "departamento":"LIMA",
   "provincia":"LIMA",
   "distrito":"LA VICTORIA",
   "fechaInscripcion":"2006-05-18T00:00:00.000Z",
   "sistEmsion":"MANUAL\/COMPUTARIZADO",
   "sistContabilidad":"MANUAL\/COMPUTARIZADO",
   "actExterior":"EXPORTADOR",
   "actEconomicas":[
      "1410 - FABRICACI\u00d3N DE PRENDAS DE VESTIR, EXCEPTO PRENDAS DE PIEL"
   ],
   "cpPago":[
      "FACTURA",
      "BOLETA DE VENTA",
      "NOTA DE CREDITO",
      "NOTA DE DEBITO",
      "GUIA DE REMISION - REMITENTE"
   ],
   "sistElectronica":[

   ],
   "fechaEmisorFe":null,
   "cpeElectronico":[

   ],
   "fechaPle":"2014-01-01T00:00:00.000Z",
   "padrones":[
      "NINGUNO"
   ]
}
```