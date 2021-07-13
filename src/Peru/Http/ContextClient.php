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
 * @deprecated obsoleto desde version 4.4.4, usar en su lugar Peru\Http\CurlClient
 */
class ContextClient implements ClientInterface
{
    private const FORM_CONTENT_TYPE = 'application/x-www-form-urlencoded';
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36';
    private const HTTP_VERSION = 1.1;

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

        return $this->getResponsePostAndSaveCookies($url, $ctx);
    }

    /**
     * @param string $method
     * @param mixed $data
     * @param array $headers
     *
     * @return resource
     */
    private function getContext(string $method, $data, array $headers)
    {
        $headers['Connection'] = 'close';
        $defaultOptions = [
            'http' => [
                'header' => $this->join(': ', $headers),
                'method' => $method,
                'content' => $this->getRawData($data),
                'user_agent' => self::USER_AGENT,
                'protocol_version' => self::HTTP_VERSION,
            ],
        ];

        if (!empty($this->options) && is_array($this->options)) {
            $defaultOptions = $this->mergeOptions($defaultOptions, $this->options);
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
            $this->cookies = array_merge($this->cookies ?? [], $responseCookies);
        }
    }

    private function getRawData($data)
    {
        return is_array($data) ? http_build_query($data) : $data;
    }

    private function join(string $glue, array $items, string $end = "\r\n"): string
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

    private function getResponsePostAndSaveCookies(string $url, $ctx)
    {
        $fp = @fopen($url, 'r', false, $ctx);
        $length = 0;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $hdr) {
                if (preg_match('/^Content-Length:\s*([^;]+)/', $hdr, $matches)) {
                    $length = (int)$matches[1];
                    break;
                }
            }
        }

        if ($length === 0) {
            $response = '';
            while (($buffer = fgets($fp, 1024)) !== false) {
                $response.=$buffer;
                if (strpos($buffer, '</body>') !== false) {
                    $response.='</html>';
                    break;
                }
            }
        } else {
            $response = @stream_get_contents($fp, $length);
        }
        fclose($fp);

        if (isset($http_response_header)) {
            $this->saveCookies($http_response_header);
        }

        return $response;
    }

    private function mergeOptions(array $default, array $overwrite): array
    {
        $merged = $default;
        foreach($overwrite as $key => $value) {
            if (array_key_exists($key, $default) && is_array($value)) {
                $merged[$key] = $this->mergeOptions($default[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
