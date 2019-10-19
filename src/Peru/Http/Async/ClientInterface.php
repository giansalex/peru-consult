<?php

namespace Peru\Http\Async;

use React\Promise\PromiseInterface;

interface ClientInterface
{
    /**
     * Make GET Request.
     *
     * @param string $url
     * @param array  $headers
     *
     * @return PromiseInterface
     */
    public function getAsync(string $url, array $headers = []): PromiseInterface;

    /**
     * Post Request.
     *
     * @param string $url
     * @param mixed  $data
     * @param array  $headers
     *
     * @return PromiseInterface
     */
    public function postAsync(string $url, $data, array $headers = []): PromiseInterface;
}
