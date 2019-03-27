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
    private const FORM_CONTENT_TYPE = 'application/x-www-form-urlencoded';
    /**
     * stream_context extra options.
     *
     * @var array
     */
    public $options;

    /**
     * Cookies store.
     *
     * @var array
     */
    public $cookies;

    /**
     * Get Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return string|false
     */
    public function get(string $url, array $headers = [])
    {
        $ctx = $this->getContext('GET', null, $headers);
        $response = file_get_contents($url, false, $ctx);
        $this->saveCookies($http_response_header);

        return $response;
    }

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed  $data
     * @param array  $headers
     *
     * @return string|false
     */
    public function post(string $url, $data, array $headers = [])
    {
        if (is_array($data)) {
            $headers['Content-type'] = self::FORM_CONTENT_TYPE;
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
    private function getContext(string $method, $data, array $headers)
    {
        $options = [
            'http' => [
                'header' => $this->join(': ', $headers),
                'method' => $method,
                'content' => $this->getRawData($data),
            ],
        ];

        if (is_array($this->options)) {
            $options = array_merge_recursive($options, $this->options);
        }

        if (!empty($this->cookies)) {
            $options['http']['header'] .= 'Cookie: '.$this->join('=', $this->cookies, '; ');
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

    private function getRawData($data)
    {
        return is_array($data) ? http_build_query($data) : $data;
    }

    private function join(string $glue, array $items, string $end = "\r\n"): ?string
    {
        $append = '';
        foreach ($items as $key => $value) {
            $append .= $key.$glue.$value.$end;
        }

        return $append;
    }
}
