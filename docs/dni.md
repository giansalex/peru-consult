# DNI
Consulta de DNI.
> Fuente: **JNE**.

## Ejemplo

```php
use Peru\Http\ContextClient;
use Peru\Jne\{Dni, DniParser};

require 'vendor/autoload.php';

$dni = '46658592';

$cs = new Dni(new ContextClient(), new DniParser());

$person = $cs->get($dni);
if (!$person) {
    echo 'Not found';
    exit();
}

echo json_encode($person);

```

## Resultado

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
