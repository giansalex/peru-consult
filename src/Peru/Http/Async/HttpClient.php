<?php

namespace Peru\Http\Async;

use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;
use React\Promise\PromiseInterface;

/**
 * Class HttpClient.
 *
 * HttpClient based on ReactPHP
 */
class HttpClient extends Browser implements ClientInterface
{
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36';

    /**
     * @var array
     */
    public $cookies;

    /**
     * Make GET Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return PromiseInterface
     */
    public function getAsync(string $url, array $headers = []): PromiseInterface
    {
        $headers = $this->buildHeaders($headers);
        return $this->get($url, $headers)
                    ->then(function (ResponseInterface $response) {
                        $this->saveCookies($response->getHeaders());

                        return (string)$response->getBody();
                    });
    }

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed $data
     * @param array $headers
     *
     * @return PromiseInterface
     */
    public function postAsync(string $url, $data, array $headers = []): PromiseInterface
    {
        $headers = $this->buildHeaders($headers);
        return $this->post($url, $headers, $data)
            ->then(function (ResponseInterface $response) {
                $this->saveCookies($response->getHeaders());

                return (string)$response->getBody();
            });
    }

    private function saveCookies(array $headers)
    {
        if (!isset($headers['Set-Cookie'])) {
            return;
        }
        $responseCookies = $headers['Set-Cookie'];
        $responseCookies = is_string($responseCookies) ? [$responseCookies] : $responseCookies;

        $this->cookies = array_merge($this->cookies ?? [], array_map(function ($cookie) {
            $pos = strpos($cookie, ';');

            return $pos === false ? $cookie : substr($cookie, 0, $pos);
        }, $responseCookies));
    }

    private function buildHeaders(array $headers)
    {
        $defaultHeaders = [
            'User-Agent' => self::USER_AGENT,
        ];

        if (!empty($this->cookies)) {
            $defaultHeaders['Cookie'] = implode('; ', $this->cookies);
        }

        return array_merge($defaultHeaders, $headers);
    }
}
