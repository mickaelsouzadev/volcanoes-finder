<?php

namespace Mickaelsouzadev\VolcanoesFinder\Service;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Mickaelsouzadev\VolcanoesFinder\Service\Interface\HttpClientInterface;

abstract class HttpClient implements HttpClientInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = $this->setClient();
    }

    public function get(string $uri, ?array $params): ResponseInterface
    {
        $options = [];
        if ($params) {
            $options = $params;
        }

        return $this->client->get($uri, $options);
    }

    abstract protected function setClient(): Client;
}
