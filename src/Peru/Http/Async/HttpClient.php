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

                        return $response->getBody();
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

                return $response->getBody();
            });
    }

    private function saveCookies(array $headers)
    {
        if (!isset($headers['Set-Cookie'])) {
            return;
        }
        $responseCookies = $headers['Set-Cookie'];
        if (is_string($responseCookies)) {
            $responseCookies = [$responseCookies];
        }

        $this->cookies = array_map(function ($cookie) {
            $pos = strpos($cookie, ';');

            return substr($cookie, 0, $pos);
        }, $responseCookies);
    }

    private function buildHeaders(array $headers)
    {
        if (empty($this->cookies)) {
            return $headers;
        }

        $headers['Cookie'] = implode('; ', $this->cookies);

        return $headers;
    }
}
