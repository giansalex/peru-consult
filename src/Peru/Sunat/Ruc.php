<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM.
 */

namespace Peru\Sunat;

use Peru\Http\ClientInterface;

/**
 * Class Ruc.
 */
class Ruc
{
    const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';

    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var HtmlParser
     */
    private $parser;

    /**
     * Ruc constructor.
     */
    public function __construct()
    {
        $this->parser = new HtmlParser();
    }

    /**
     * @param string $ruc
     *
     * @return bool|Company
     */
    public function get($ruc)
    {
        if (strlen($ruc) !== 11) {
            $this->error = 'Ruc debe tener 11 dígitos';

            return false;
        }
        $random = $this->getRandom();
        $url = self::URL_CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=";
        $dic = $this->getValuesFromUrl($url);

        if ($dic === false) {
            return false;
        }

        return $this->getCompany($dic);
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

    private function getRandom()
    {
        $code = $this->client->get(self::URL_RANDOM);

        return $code;
    }

    private function getValuesFromUrl($url)
    {
        $html = $this->client->get($url);

        if ($html === false) {
            $this->error = 'Ocurrio un problema conectando a Sunat';

            return false;
        }

        $dic = $this->parser->parse($html);
        if ($dic === false) {
            $this->error = 'No se encontro el ruc';

            return false;
        }

        return $dic;
    }

    private function getCompany(array $items)
    {
        $cp = $this->getHeadCompany($items);
        $cp->sistEmsion = $items['Sistema de Emisión de Comprobante:'];
        $cp->sistContabilidad = $items['Sistema de Contabilidad:'];
        $cp->actExterior = $items['Actividad de Comercio Exterior:'];
        $cp->actEconomicas = $items['Actividad(es) Económica(s):'];
        $cp->cpPago = $items['Comprobantes de Pago c/aut. de impresión (F. 806 u 816):'];
        $cp->sistElectronica = $items['Sistema de Emision Electronica:'];
        $cp->fechaEmisorFe = $this->parseDate($items['Emisor electrónico desde:']);
        $cp->cpeElectronico = $this->getCpes($items['Comprobantes Electrónicos:']);
        $cp->fechaPle = $this->parseDate($items['Afiliado al PLE desde:']);
        $cp->padrones = $items['Padrones :'];
        if ($cp->sistElectronica == '-') {
            $cp->sistElectronica = [];
        }
        $this->fixDirection($cp);

        return $cp;
    }

    private function getHeadCompany(array $items)
    {
        $cp = new Company();

        list($cp->ruc, $cp->razonSocial) = $this->getRucRzSocial($items['Número de RUC:']);
        $cp->nombreComercial = $items['Nombre Comercial:'];
        $cp->telefonos = $items['Phone'];
        $cp->tipo = $items['Tipo Contribuyente:'];
        $cp->estado = $items['Estado del Contribuyente:'];
        $cp->condicion = $items['Condición del Contribuyente:'];
        $cp->direccion = $items['Dirección del Domicilio Fiscal:'];
        $cp->fechaInscripcion = $this->parseDate($items['Fecha de Inscripción:']);

        return $cp;
    }

    /**
     * @param $text
     *
     * @return null|string
     */
    private function parseDate($text)
    {
        if (empty($text) || $text == '-') {
            return null;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $text);

        return $date === false ? null : $date->format('Y-m-d').'T00:00:00.000Z';
    }

    private function fixDirection(Company $company)
    {
        $items = explode('                                               -', $company->direccion);
        if (count($items) !== 3) {
            $company->direccion = preg_replace("[\s+]", ' ', $company->direccion);

            return;
        }

        $pieces = explode(' ', trim($items[0]));
        list($len, $value) = $this->getDepartment(array_pop($pieces));
        $company->departamento = $value;
        $company->provincia = trim($items[1]);
        $company->distrito = trim($items[2]);
        array_splice($pieces, -1 * $len);
        $company->direccion = join(' ', $pieces);
    }

    private function getDepartment($department)
    {
        $department = strtoupper($department);
        $words = 1;
        switch ($department) {
            case 'DIOS':
                $department = 'MADRE DE DIOS';
                $words = 3;
            break;
            case 'MARTIN':
                $department = 'SAN MARTIN';
                $words = 2;
            break;
            case 'LIBERTAD':
                $department = 'LA LIBERTAD';
                $words = 2;
            break;
        }

        return [$words, $department];
    }

    private function getCpes($text)
    {
        $cpes = [];
        if ($text != '-') {
            $cpes = explode(',', $text);
        }

        return $cpes;
    }

    private function getRucRzSocial($text)
    {
        $pos = strpos($text, '-');

        $ruc = trim(substr($text, 0, $pos));
        $rzSocial = trim(substr($text, $pos + 1));

        return [$ruc, $rzSocial];
    }
}
