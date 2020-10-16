# DNI
Consulta de DNI.
> Fuente: **JNE**.

## Ejemplo

```php
use Peru\Jne\DniFactory;

require 'vendor/autoload.php';

$dni = '46658592';

$factory = new DniFactory();
$cs = $factory->create();

$person = $cs->get($dni);
if (!$person) {
    echo 'Not found';
    return;
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
