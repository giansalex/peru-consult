<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 24/09/2018
 * Time: 11:25.
 */

namespace Peru\Jne;

use Peru\Http\ClientInterface;
use Peru\Reniec\Person;
use Peru\Services\DniInterface;

/**
 * Class Dni.
 */
class Dni implements DniInterface
{
    private const URL_CONSULT_FORMAT = 'http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI=%s';
    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Get Person Information by DNI.
     *
     * @param string $dni
     *
     * @return Person|null
     */
    public function get(string $dni): ?Person
    {
        if (8 !== strlen($dni)) {
            $this->error = 'Dni debe tener 8 dígitos';

            return null;
        }

        $raw = $this->getRawResponse($dni);
        if (false === $raw) {
            return null;
        }

        $person = $this->getPerson($raw);
        if ($person) {
            $person->dni = $dni;
        }

        return $person;
    }

    /**
     * Set Custom Http Client.
     *
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get Last error message.
     *
     * @return string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    private function getRawResponse(string $dni)
    {
        $url = sprintf(self::URL_CONSULT_FORMAT, $dni);
        $text = $this->client->get($url);

        if (false === $text) {
            $this->error = 'No se pudo conectar a JNE';

            return false;
        }

        return $text;
    }

    private function getPerson($text): ?Person
    {
        $parts = explode('|', $text);
        if (count($parts) < 3) {
            $this->error = $text;

            return null;
        }

        $person = new Person();
        $person->apellidoPaterno = $parts[0];
        $person->apellidoMaterno = $parts[1];
        $person->nombres = $parts[2];

        return $person;
    }
}
