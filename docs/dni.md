# DNI
Consulta de DNI.
> Fuente: **JNE**.

Ejemplo
--------

```php
use Peru\Jne\Dni;

require 'vendor/autoload.php';

$dni = '46658592';

$cs = new Dni();

$person = $cs->get($dni);
if (!$person) {
    echo $cs->getError();
    exit();
}

echo json_encode($person);

```

Resultado
---------

Resultado en formato json.

```json
{
  "dni": "46658592",
  "nombres": "LESLY LICET",
  "apellidoPaterno": "PEREZ",
  "apellidoMaterno": "PEÑA",
  "codVerifica": "6"
}
```
