# RUC

Consulta del RUC usando el servicio público de Sunat.

Ejemplo
--------

```php
use Peru\Sunat\Ruc;

require 'vendor/autload.php';

$myDni = '20513176962';

$cs = new Ruc();
$company = $cs->get($dni);
if ($company === false) {
	echo $cs->getError();
	exit();
}

var_dump($company);

```