<?php

namespace Mickaelsouzadev\VolcanoesFinder\Service;

use GuzzleHttp\Client;

class GlobalVolcanismService extends HttpClient
{
    const BASE_URI = 'https://volcano.si.edu/';
    const HOLOCENE_VOLCANOES_PAGE = 'volcanolist_holocene.cfm';

    public function getHoloceneVolcanoesList(): string
    {
        $response = $this->get(self::HOLOCENE_VOLCANOES_PAGE, null);
        return $response->getBody()->getContents();
    }

    protected function setClient(): Client
    {
        return new Client(
            [
                'verify' => false,
                'base_uri' => self::BASE_URI,
            ]
        );
    }
}
