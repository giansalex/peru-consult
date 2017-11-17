<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 15/11/2017
 * Time: 04:15 PM
 */

namespace Peru\Reniec;

use Peru\CookieRequest;

/**
 * Class Dni
 * @package Peru\Reniec
 */
class Dni extends CookieRequest
{
    const URL_CONSULT = 'https://cel.reniec.gob.pe/valreg/valreg.do';
    const URL_CAPTCHA = 'https://cel.reniec.gob.pe/valreg/codigo.do';
    private $error;

    /**
     * @param string $dni
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
            $this->error = 'No se pudo iniciar la consulta';
            return false;
        }

        $req = $this->getCurl();
        $page = $req->post(self::URL_CONSULT, [
            "accion" 	=> "buscar",
            "nuDni" 	=> $dni,
            "imagen" 	=> $captcha
        ]);

        if ($req->error) {
            $this->error = 'Ocurrio un problema conectando a Reniec';
            return false;
        }

        $person = $this->getPerson($page);
        if (empty($person->nombres)) {
            $this->error = 'No se encontro resultados para el dni';
            return false;
        }
        $person->dni = $dni;

        return $person;
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
        if( isset($matches[0]) )
        {
            $person->nombres = utf8_encode($matches[0][1]);
            $person->apellidoPaterno = utf8_encode($matches[0][2]);
            $person->apellidoMaterno = utf8_encode($matches[0][3]);
        }

        $patron='/<font color=#ff0000>([A-Z0-9]+) <\/font>/';
        preg_match_all($patron, $html, $matches, PREG_SET_ORDER);
        if( isset($matches[0]) )
        {
            $person->codVerifica = trim($matches[0][1]);
        }

        return $person;
    }

    /**
     * Get Captcha image.
     *
     * @return bool|mixed
     */
    private function getCaptchaImage()
    {
        $req = $this->getCurl();
        $image = $req->get(self::URL_CAPTCHA);

        if ($req->error) {
            return false;
        }

        return $image;
    }

    /**
     * Get Captcha from String.
     *
     * @return bool|string
     */
    function getCatpchaValue()
    {
        $captcha = $this->getCaptchaImage();

        if ($captcha === false) {
            return false;
        }

        $image = imagecreatefromstring($captcha);
        if (!$image) {
            return false;
        }

        imagefilter($image, IMG_FILTER_GRAYSCALE);
        imagefilter($image, IMG_FILTER_BRIGHTNESS,100);
        imagefilter($image, IMG_FILTER_NEGATE);
        $L1 = imagecreatetruecolor(25, 20);
        $L2 = imagecreatetruecolor(25, 20);
        $L3 = imagecreatetruecolor(25, 20);
        $L4 = imagecreatetruecolor(25, 20);

        imagecopyresampled($L1, $image, 0, 0, 13, 10, 25, 20, 25, 20);
        imagecopyresampled($L2, $image, 0, 0, 43, 15, 25, 20, 25, 20);
        imagecopyresampled($L3, $image, 0, 0, 76, 10, 25, 20, 25, 20);
        imagecopyresampled($L4, $image, 0, 0, 106,15, 25, 20, 25, 20);

        $query = <<<SQL
SELECT (SELECT Caracter FROM Diccionario WHERE Codigo1='{$this->getText($L1)}') AS c1,
(SELECT Caracter FROM Diccionario WHERE Codigo2='{$this->getText($L2)}') AS c2,
(SELECT Caracter FROM Diccionario WHERE Codigo3='{$this->getText($L3)}') AS c3,
(SELECT Caracter FROM Diccionario WHERE Codigo4='{$this->getText($L4)}') AS c4
SQL;

        $rpt = $this->getConnection()->query($query);
        if($row = $rpt->fetch(\PDO::FETCH_ASSOC))
        {
            return $row["c1"] . $row["c2"] . $row["c3"] . $row["c4"];
        }

        return false;
    }

    /**
     * Retorna 1 o 0
     * @param $image
     * @return string
     */
    function getText($image)
    {
        $rtn="";
        $w = imagesx($image);
        $h = imagesy($image);
        for($y=0; $y<$h;$y++)
        {
            for($x=0; $x<$w;$x++)
            {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                if((($r+$g+$b)/255) < 1)
                {
                    $rtn .= "0";
                }
                else
                {
                    $rtn .= "1";
                }
            }
        }

        return $rtn;
    }

    /**
     * Get Db Connection.
     * @return \PDO
     */
    private function getConnection()
    {
        return new \PDO('sqlite:' . __DIR__ . '/solver.db');
    }
}