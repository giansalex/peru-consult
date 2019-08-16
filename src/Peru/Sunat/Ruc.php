<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM.
 */

namespace Peru\Sunat;

use Peru\Http\ClientInterface;
use Peru\Http\ContextClient;

/**
 * Class Ruc.
 */
class Ruc
{
    private const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    private const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';

    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var RucHtmlParser
     */
    private $parser;

    /**
     * Get Company Information by RUC.
     *
     * @param string $ruc
     *
     * @return null|Company
     */
    public function get(string $ruc): ?Company
    {
        if (11 !== strlen($ruc)) {
            $this->error = 'Ruc debe tener 11 dígitos';

            return null;
        }
        $this->validateDependencies();

        $random = $this->getHttpResponse(self::URL_RANDOM);
        $html = $this->getHttpResponse(self::URL_CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=");

        return $this->parser->parse($html);
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
     * Set Html Parser.
     *
     * @param HtmlParser $parser
     */
    public function setParser(HtmlParser $parser)
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
            $this->client = new ContextClient();
        }

        if (empty($this->parser)) {
            $this->parser = new RucHtmlParser(new HtmlParser());
        }
    }

    private function getHttpResponse(string $url): ?string
    {
        $body = $this->client->get($url);

        return false === $body ? '' : $body;
    }
}
