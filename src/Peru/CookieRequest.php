<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/11/2017
 * Time: 20:04
 */

namespace Peru;

use Curl\Curl;

/**
 * Class CookieRequest
 * @package Peru
 */
class CookieRequest
{
    /**
     * @var array
     */
    private $cookies;

    /**
     * CookieRequest constructor.
     */
    public function __construct()
    {
        $this->clearCookies();
    }


    /**
     * @return Curl
     */
    public function getCurl()
    {
        $curl = new Curl();
        if (!empty($this->cookies)) {
            $curl->setCookies($this->cookies);
        }
        $curl->completeFunction = function (Curl $instance) {
            $this->cookies = array_merge($this->cookies, $instance->responseCookies);
        };

        return $curl;
    }

    public function clearCookies()
    {
        $this->cookies = [];
    }
}
