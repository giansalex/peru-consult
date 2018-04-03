# DNI
Consulta del DNI usando el servicio público de Reniec.

Requerimientos
---------------
- Tener cargada las extensiones `gd` y `openssl`.

Ejemplo
--------

```php
use Peru\Reniec\Dni;

require 'vendor/autoload.php';

$dni = '46658592';

$cs = new Dni();
$cs->setClient(new ContextClient());

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
  "dni": "46658592",
  "nombres": "LESLY LICET",
  "apellidoPaterno": "PEREZ",
  "apellidoMaterno": "PEÑA",
  "codVerifica": "6"
}
```