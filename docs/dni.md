# DNI
Consulta del DNI.

Ejemplo
--------

```php
use Peru\Reniec\Dni;
use Peru\Http\ContextClient;

require 'vendor/autoload.php';

$dni = '46658592';

$cs = new Dni();
$cs->setClient(new ContextClient());

$person = $cs->get($dni);
if ($person === false) {
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
