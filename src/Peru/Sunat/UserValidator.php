<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 01/04/2018
 * Time: 09:16.
 */

namespace Peru\Sunat;

use Peru\Http\ClientInterface;

/**
 * Class UserValidator.
 */
class UserValidator
{
    const URL_VALIDEZ = 'http://www.sunat.gob.pe/cl-ti-itestadousr/usrS00Alias';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * UserValidator constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Consulta válidez del usuario SOL.
     *
     * @param string $ruc
     * @param string $user
     *
     * @return bool
     */
    public function valid($ruc, $user)
    {
        $this->client->get(self::URL_VALIDEZ);
        $html = $this->client->post(self::URL_VALIDEZ, [
            'accion' => 'e1',
            'ruc' => $ruc,
            'usr' => $user,
        ]);

        $state = $this->getStatus($html);

        return false !== strpos(strtoupper($state), 'ACTIVO');
    }

    private function getStatus($html)
    {
        $xpt = HtmlParser::getXpathFromHtml($html);
        $nodes = $xpt->query('//strong');

        if (1 !== $nodes->length) {
            return '';
        }

        return $nodes->item(0)->nodeValue;
    }
}
