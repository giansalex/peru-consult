<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/01/2018
 * Time: 15:59.
 */

namespace Peru\Http;

/**
 * Interface ClientInterface.
 */
interface ClientInterface
{
    /**
     * Get Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return string|bool
     */
    public function get($url, array $headers = []);

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed  $data
     * @param array  $headers
     *
     * @return string|bool
     */
    public function post($url, $data, array $headers = []);
}
