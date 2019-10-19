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
    private const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/3.0.0.1';
    
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

        return $this->getResponseAndSaveCookies($url, $ctx);
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

        return $this->getResponseAndSaveCookies($url, $ctx);
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
        $defaultOptions = [
            'http' => [
                'header' => $this->join(': ', $headers),
                'method' => $method,
                'content' => $this->getRawData($data),
                'user_agent' => self::USER_AGENT,
            ],
        ];

        if (!empty($this->options)) {
            $defaultOptions = array_merge_recursive($defaultOptions, $this->options);
        }

        if (!empty($this->cookies)) {
            $defaultOptions['http']['header'] .= 'Cookie: ' . $this->join('=', $this->cookies, '; ');
        }

        return stream_context_create($defaultOptions);
    }

    private function saveCookies(array $headers)
    {
        $responseCookies = [];
        foreach ($headers as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $tmp);
                $responseCookies = array_merge($responseCookies, $tmp);
            }
        }

        if (!empty($responseCookies)) {
            $this->cookies = $responseCookies;
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
            $append .= $key . $glue . $value . $end;
        }

        return $append;
    }

    private function getResponseAndSaveCookies(string $url, $ctx)
    {
        $response = @file_get_contents($url, false, $ctx);

        if (isset($http_response_header)) {
            $this->saveCookies($http_response_header);
        }

        return $response;
    }
}
