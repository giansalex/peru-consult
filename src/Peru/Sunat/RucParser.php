<?php

namespace Peru\Sunat;

use DateTime;

class RucParser
{
    /**
     * @var string
     */
    private $error;

    /**
     * @var HtmlParser
     */
    private $parser;

    /**
     * RucHtmlParser constructor.
     * @param HtmlParser $parser
     */
    public function __construct(HtmlParser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(string $html): ?Company
    {
        if (empty($html)) {
            return null;
        }

        $dic = $this->parser->parse($html);
        if (false === $dic) {
            $this->error = 'No se encontro el ruc';

            return null;
        }

        return $this->getCompany($dic);
    }

    private function getCompany(array $items): ?Company
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
        if ('-' == $cp->sistElectronica) {
            $cp->sistElectronica = [];
        }
        $this->fixDirection($cp);

        return $cp;
    }

    private function getHeadCompany(array $items): ?Company
    {
        $cp = new Company();

        list($cp->ruc, $cp->razonSocial) = $this->getRucRzSocial($items['Número de RUC:']);
        $cp->nombreComercial = $items['Nombre Comercial:'];
        $cp->telefonos = [];
        $cp->tipo = $items['Tipo Contribuyente:'];
        $cp->estado = $items['Estado del Contribuyente:'];
        $cp->condicion = $items['Condición del Contribuyente:'];
        $cp->direccion = $items['Dirección del Domicilio Fiscal:'];
        $cp->fechaInscripcion = $this->parseDate($items['Fecha de Inscripción:']);
        $cp->fechaBaja = $this->parseDate($items['Fecha de Baja:'] ?? '-');

        return $cp;
    }

    /**
     * @param $text
     *
     * @return null|string
     */
    private function parseDate($text)
    {
        if (empty($text) || '-' == $text) {
            return null;
        }

        $date = DateTime::createFromFormat('d/m/Y', $text);

        return false === $date ? null : $date->format('Y-m-d').'T00:00:00.000Z';
    }

    private function fixDirection(Company $company)
    {
        $items = explode('                                               -', $company->direccion);
        if (3 !== count($items)) {
            $company->direccion = preg_replace("[\s+]", ' ', $company->direccion);

            return;
        }

        $pieces = explode(' ', trim($items[0]));
        $department = $this->getDepartment(end($pieces));
        $company->departamento = $department;
        $company->provincia = trim($items[1]);
        $company->distrito = trim($items[2]);
        $removeLength = count(explode(' ', $department));
        array_splice($pieces, -1 * $removeLength);
        $company->direccion = join(' ', $pieces);
    }

    private function getDepartment($department): string
    {
        $department = strtoupper($department);
        switch ($department) {
            case 'DIOS':
                $department = 'MADRE DE DIOS';
                break;
            case 'MARTIN':
                $department = 'SAN MARTIN';
                break;
            case 'LIBERTAD':
                $department = 'LA LIBERTAD';
                break;
        }

        return $department;
    }

    private function getCpes($text)
    {
        $cpes = [];
        if ('-' != $text) {
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
