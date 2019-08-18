# Usuario SOL
Consulta la válidez del usuario SOL.
> Fuente: **SUNAT**.

## Requerimientos
- Tener activada la extensión `dom`.

## Ejemplo

```php
use Peru\Http\ContextClient;
use Peru\Sunat\UserValidator;

require 'vendor/autoload.php';

$ruc = '20123456789'; // colocar un ruc válido
$user = 'TGGMMSYY'; // colocar un usuario según el ruc

$cs = new UserValidator(new ContextClient());
$valid = $cs->valid($dni);
if ($valid) {
    echo 'Válido';
} else {
    echo 'Inválido';
}

```

## Resultado

Resultado en consola

```
Válido
```