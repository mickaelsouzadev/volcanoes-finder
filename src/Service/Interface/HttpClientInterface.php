<?php

namespace Mickaelsouzadev\VolcanoesFinder\Service\Interface;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function get(string $uri, ?array $params): ResponseInterface;
}
