<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/01/2018
 * Time: 16:03.
 */

namespace Peru\Http;

/**
 * Stream Context Client.
 *
 * Class ContextClient
 */
class ContextClient implements ClientInterface
{
    /**
     * @var array
     */
    private $cookies;

    /**
     * Get Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return string|false
     */
    public function get($url, array $headers)
    {
        $ctx = $this->getContext('POST', null, $headers);
        $response = file_get_contents($url, false, $ctx);
        $this->saveCookies($http_response_header);

        return $response;
    }

    /**
     * Post Request.
     *
     * @param string $url
     * @param array  $headers
     * @param mixed  $data
     *
     * @return string|bool
     */
    public function post($url, array $headers, $data)
    {
        if (is_array($data)) {
            $headers['Content-type'] = 'application/x-www-form-urlencoded';
        }

        $ctx = $this->getContext('POST', $data, $headers);
        $response = file_get_contents($url, false, $ctx);
        $this->saveCookies($http_response_header);

        return $response;
    }

    /**
     * @param string $method
     * @param $data
     * @param array $headers
     *
     * @return resource
     */
    private function getContext($method, $data, array $headers)
    {
        $append = '';
        foreach ($headers as $key => $value) {
            $append .= $key.': '.$value."\r\n";
        }

        $options = [
            'http' => [
                'header' => $append,
                'method' => $method,
                'content' => is_array($data) ? http_build_query($data) : $data,
            ],
        ];
        if (!empty($this->cookies)) {
            $append = '';
            foreach ($this->cookies as $key => $value) {
                $append .= $key.'='.$value."\r\n";
            }
            $options['http']['header'] .= 'Cookie: '.$append;
        }

        $context = stream_context_create($options);

        return $context;
    }

    private function saveCookies(array $headers)
    {
        $cookies = [];
        foreach ($headers as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $tmp);
                $cookies = array_merge($cookies, $tmp);
            }
        }

        if (!empty($cookies)) {
            $this->cookies = $cookies;
        }
    }
}
