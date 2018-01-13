# DNI
Consulta del DNI usando el servicio público de Reniec.

Requerimientos
---------------
- Tener cargada la extension `gd`.

Ejemplo
--------

```php
use Peru\Reniec\Dni;

require 'vendor/autoload.php';

$dni = '00000004';

$cs = new Dni();
$person = $cs->get($dni);
if ($person === false) {
    echo $cs->getError();
    exit();
}

echo json_encode(get_object_vars($person));

```

Resultado
---------

Resultado en formato json.

```json
{
    "dni":"00000004",
   "nombres":"JOYCE",
   "apellidoPaterno":"BARDALES",
   "apellidoMaterno":"TORRES",
   "codVerifica":"9"
}
```