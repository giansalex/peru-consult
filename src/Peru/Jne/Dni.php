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
     * @var DniParser
     */
    private $parser;

    /**
     * Dni constructor.
     *
     * @param ClientInterface $client
     * @param DniParser       $parser
     */
    public function __construct(ClientInterface $client, DniParser $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

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

        $url = sprintf(self::URL_CONSULT_FORMAT, $dni);
        $raw = $this->client->get($url);

        return $this->parser->parse($dni, $raw);
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
}
