<?php

namespace Peru\Http\Async;

use React\HttpClient\Client;
use React\HttpClient\Response;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

/**
 * Class HttpClient.
 *
 * HttpClient based on ReactPHP
 */
class HttpClient extends Client implements ClientInterface
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
        return $this->requestAsync('GET', $url, null, $headers);
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
        return $this->requestAsync('POST', $url, $data, $headers);
    }

    private function requestAsync($method, $url, $data, $headers)
    {
        $deferred = new Deferred();
        $headers = $this->buildHeaders($headers);

        $request = $this->request($method, $url, $headers);
        $request->on('response', function (Response $response) use ($deferred) {
            $this->saveCookies($response->getHeaders());

            $result = '';
            $response->on('data', function ($data) use (&$result) {
                $result .= $data;
            });

            $response->on('end', function () use (&$result, $deferred) {
                $deferred->resolve($result);
            });
        });
        $request->on('error', function ($e) use ($deferred) {
            $deferred->reject($e);
        });
        $request->end($data);

        return $deferred->promise();
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
