# DNI
Consulta del DNI usando el servicio público de Reniec.

Ejemplo
--------

```php
use Peru\Reniec\Dni;

require 'vendor/autload.php';

$myDni = '00000004';

$cs = new Dni();
$person = $cs->get($dni);
if ($person === false) {
	echo $cs->getError();
	exit();
}

var_dump($person);

```