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
> Para cambiar el __JNE token__, puedes hacerlo con el metodo `$cs->setRequestToken('097n0wui1....4I6yIMF2xGS')`
> Más información en [issue #29](https://github.com/giansalex/peru-consult/issues/29)

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
