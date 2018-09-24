<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 24/09/2018
 * Time: 11:25
 */

namespace Peru\Jne;

use Peru\Http\ClientInterface;
use Peru\Reniec\Person;

/**
 * Class Dni
 */
class Dni
{
    const URL_CONSULT_FORMAT = 'http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI=%s';
    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    private $client;


    public function get($dni)
    {
        if (strlen($dni) !== 8) {
            $this->error = 'Dni debe tener 8 dígitos';

            return false;
        }

        $raw = $this->getRawResponse($dni);
        if ($raw === false) {
            return false;
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
    public function getError()
    {
        return $this->error;
    }

    private function getRawResponse($dni)
    {
        $url = sprintf(self::URL_CONSULT_FORMAT, $dni);
        $text = $this->client->get($url);

        if ($text === false) {
            $this->error = 'No se pudo conectar a JNE';

            return false;
        }

        return $text;
    }

    private function getPerson($text)
    {
        $parts = explode('|', $text);
        if (count($parts) === 3) {
            $person = new Person();
            $person->apellidoPaterno = $parts[0];
            $person->apellidoMaterno = $parts[1];
            $person->nombres         = $parts[2];

            return $person;
        }
        $this->error = implode('', $parts);

        return false;
    }
}