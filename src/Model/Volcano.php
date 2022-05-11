<?php

namespace Mickaelsouzadev\VolcanoesFinder\Model;

use Symfony\Component\DomCrawler\Crawler;
use Mickaelsouzadev\VolcanoesFinder\Service\GlobalVolcanismService;
use Mickaelsouzadev\VolcanoesFinder\Helper\FileHandler;

class Volcano
{
    private GlobalVolcanismService $globalVolcanismService;
    private Crawler $crawler;
    private FileHandler $fileHandler;

    public function __construct(
        GlobalVolcanismService $globalVolcanismService,
        Crawler $crawler,
        FileHandler $fileHandler
    ) {
        $this->globalVolcanismService = $globalVolcanismService;
        $this->crawler = $crawler;
        $this->fileHandler = $fileHandler;
    }

    public function listVolcanoes(): array
    {
        $formatedVolcanoes = $this->getFormatedVolcanoes();
        return $formatedVolcanoes;
    }

    private function getFormatedVolcanoes(): array
    {
        $volcanoes = $this->getVolcanoes();
        $filteredVolcanoes = array_filter($volcanoes, fn ($volcano) => count($volcano) != 0);
        $formatedVolcanoes = array_map(
            fn ($volcano) => [
                'name' => $volcano[0],
                'type' => $volcano[1],
                'location' => $volcano[2]
            ],
            $filteredVolcanoes
        );

        return $formatedVolcanoes;
    }

    private function getVolcanoes(): array
    {
        $volcanoesFromFile = $this->fileHandler->read('volcanoes.json');
        if (false !== $volcanoesFromFile) {
            return json_decode($volcanoesFromFile, true);
        }

        $volcanoesFromWeb = $this->getVolcanoesFromWeb();
        $this->saveVolcanoesInLocalFile($volcanoesFromWeb);

        return $volcanoesFromWeb;
    }

    private function getVolcanoesFromWeb(): array
    {
        $htmlResult = $this->globalVolcanismService->getHoloceneVolcanoesList();
        $this->crawler->add($htmlResult);

        $crawledTableOfResults = $this->crawler->filter('div[class="TableSearchResults"]')->filter('table')->filter('tr');
        $volcanoes = $crawledTableOfResults->each(
            fn ($tr) => $tr->filter('td')->each(
                fn ($td)  => trim($td->text())
            )
        );

        return $volcanoes;
    }

    private function saveVolcanoesInLocalFile(array $volcanoes): void
    {
        $encodedVolcanoes = json_encode($volcanoes);
        $this->fileHandler->write('volcanoes.json', $encodedVolcanoes);
    }
}
