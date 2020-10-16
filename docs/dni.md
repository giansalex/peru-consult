# DNI
Consulta de DNI.
> Fuente: **JNE**.

## Requerimientos
- Tener activo [allow_url_fopen](https://www.php.net/manual/es/filesystem.configuration.php#ini.allow-url-fopen).

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
