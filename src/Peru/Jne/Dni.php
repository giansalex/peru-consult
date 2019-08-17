<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 24/09/2018
 * Time: 11:25.
 */

namespace Peru\Jne;

use Peru\Http\ClientInterface;
use Peru\Http\ContextClient;
use Peru\Http\EmptyResponseDecorator;
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

        $this->validateDependencies();
        $url = sprintf(self::URL_CONSULT_FORMAT, $dni);
        $raw = $this->client->get($url);

        $person = $this->parser->parse($dni, $raw);

        return $person;
    }

    /**
     * Set Custom Http Client.
     *
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * @param DniParser $parser
     */
    public function setParser(DniParser $parser): void
    {
        $this->parser = $parser;
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

    private function validateDependencies()
    {
        if (empty($this->client)) {
            $this->client = new EmptyResponseDecorator(new ContextClient());
        }

        if (empty($this->parser)) {
            $this->parser = new DniParser();
        }
    }
}
