<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM.
 */

namespace Peru\Reniec;

use Peru\Http\ClientInterface;

/**
 * Class Dni.
 */
class Dni
{
    const URL_CONSULT = 'https://cel.reniec.gob.pe/valreg/valreg.do';
    const URL_CAPTCHA = 'https://cel.reniec.gob.pe/valreg/codigo.do';
    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var CaptchaCodes
     */
    private $codes;

    /**
     * Dni constructor.
     */
    public function __construct()
    {
        $this->codes = new CaptchaCodes();
    }

    /**
     * @param string $dni
     *
     * @return bool|Person
     */
    public function get($dni)
    {
        if (strlen($dni) !== 8) {
            $this->error = 'Dni debe tener 8 dígitos';

            return false;
        }
        $captcha = $this->getCatpchaValue();
        if ($captcha === false) {
            return false;
        }

        $person = $this->getResult($dni, $captcha);
        if ($person === false) {
            return false;
        }
        $person->dni = $dni;

        return $person;
    }

    private function getResult($dni, $captcha)
    {
        $page = $this->client->post(self::URL_CONSULT, [
            'accion' => 'buscar',
            'nuDni' => $dni,
            'imagen' => $captcha,
        ]);

        if ($page === false) {
            $this->error = 'Ocurrio un problema conectando a Reniec';

            return false;
        }

        $person = $this->getPerson($page);
        if (empty($person->nombres)) {
            $this->error = 'No se encontro resultados para el dni';

            return false;
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

    private function getPerson($html)
    {
        $person = new Person();
        $patron = '/<td height="63" class="style2" align="center">\r\n[ ]+(.*)\r\n[ ]+(.*)\r\n[ ]+(.*)<br>/';
        preg_match_all($patron, $html, $matches, PREG_SET_ORDER);
        if (isset($matches[0])) {
            $person->nombres = utf8_encode($matches[0][1]);
            $person->apellidoPaterno = utf8_encode($matches[0][2]);
            $person->apellidoMaterno = utf8_encode($matches[0][3]);
        }
        $this->setCodVerifica($person, $html);

        return $person;
    }

    private function setCodVerifica(Person $person, $html)
    {
        $patron = '/<font color=#ff0000>([A-Z0-9]+) <\/font>/';
        preg_match_all($patron, $html, $matches, PREG_SET_ORDER);
        if (isset($matches[0])) {
            $person->codVerifica = trim($matches[0][1]);
        }
    }

    /**
     * Get Captcha image.
     *
     * @return bool|mixed
     */
    private function getCaptchaImage()
    {
        $image = $this->client->get(self::URL_CAPTCHA);

        if ($image === false) {
            $this->error = 'No se pudo cargar el captcha image';

            return false;
        }

        return $image;
    }

    /**
     * Get Captcha from String.
     *
     * @return bool|string
     */
    private function getCatpchaValue()
    {
        $captcha = $this->getCaptchaImage();

        if ($captcha === false) {
            return false;
        }

        $image = @imagecreatefromstring($captcha);
        if (!$image) {
            $this->error = 'No se pudo crear imagen desde el captcha';

            return false;
        }

        return $this->getValueCaptchaFromImage($image);
    }

    private function getValueCaptchaFromImage($image)
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_BRIGHTNESS, 100);
        imagefilter($image, IMG_FILTER_NEGATE);
        $L1 = imagecreatetruecolor(25, 20);
        $L2 = imagecreatetruecolor(25, 20);
        $L3 = imagecreatetruecolor(25, 20);
        $L4 = imagecreatetruecolor(25, 20);

        imagecopyresampled($L1, $image, 0, 0, 13, 10, 25, 20, 25, 20);
        imagecopyresampled($L2, $image, 0, 0, 43, 15, 25, 20, 25, 20);
        imagecopyresampled($L3, $image, 0, 0, 76, 10, 25, 20, 25, 20);
        imagecopyresampled($L4, $image, 0, 0, 106, 15, 25, 20, 25, 20);

        $cod = $this->codes;
        $value = $cod->getLetter($this->getText($L1), 1);
        $value .= $cod->getLetter($this->getText($L2), 2);
        $value .= $cod->getLetter($this->getText($L3), 3);
        $value .= $cod->getLetter($this->getText($L4), 4);

        return $value;
    }

    /**
     * Retorna 1 o 0.
     *
     * @param $image
     *
     * @return string
     */
    private function getText($image)
    {
        $rtn = '';
        $w = imagesx($image);
        $h = imagesy($image);
        for ($y = 0; $y < $h; ++$y) {
            for ($x = 0; $x < $w; ++$x) {
                $rgb = imagecolorat($image, $x, $y);
                $rtn .= $this->getDotFromRgb($rgb);
            }
        }

        return $rtn;
    }

    private function getDotFromRgb($rgb)
    {
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ((($r + $g + $b) / 255) < 1) {
            return '0';
        }

        return '1';
    }
}
