<?php

namespace Mickaelsouzadev\VolcanoesFinder\Test\Unit;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Mickaelsouzadev\VolcanoesFinder\Model\Volcano;
use Symfony\Component\DomCrawler\Crawler;
use Mickaelsouzadev\VolcanoesFinder\Service\GlobalVolcanismService;
use Mickaelsouzadev\VolcanoesFinder\Helper\FileHandler;

class VolcanoUTest extends TestCase
{
    private $globalVolcanismService;
    private $crawler;
    private $fileHandler;
    private $volcano;

    protected function setUp(): void
    {
        $this->globalVolcanismService = $this->createMock(GlobalVolcanismService::class);
        $this->crawler = $this->createMock(Crawler::class);
        $this->fileHandler = $this->createMock(FileHandler::class);
        $this->volcano = new Volcano(
            $this->globalVolcanismService,
            $this->crawler,
            $this->fileHandler
        );
    }

    public function testGetFormatedVolcanoes(): void
    {
        $volcanoes = [
            [
                'Etna',
                'Stratovolcano',
                'Italy'
            ]
        ];
        $formatedVolcanoes = [
            [
                'name' => $volcanoes[0][0],
                'type' => $volcanoes[0][1],
                'location' => $volcanoes[0][2]
            ]
        ];

        $this->fileHandler
            ->expects($this->once())
            ->method('read')
            ->willReturn(json_encode($volcanoes));

        $reflectedVolcano = new ReflectionClass($this->volcano);
        $getFormatedVolcanoesMethod = $reflectedVolcano->getMethod('getFormatedVolcanoes');
        $getFormatedVolcanoesMethod->setAccessible(true);
        $result = $getFormatedVolcanoesMethod->invoke($this->volcano);
        $this->assertEquals($result, $formatedVolcanoes);
    }
}
