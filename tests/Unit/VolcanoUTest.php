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
    private $fileHandler;
    private $volcano;

    protected function setUp(): void
    {
        $this->globalVolcanismService = $this->createMock(GlobalVolcanismService::class);
        $this->fileHandler = $this->createMock(FileHandler::class);
        $this->volcano = new Volcano(
            $this->globalVolcanismService,
            new Crawler(),
            $this->fileHandler
        );
    }

    public function testListVolcanoesWithLocalFile(): void
    {
        $volcanoes = [
            [
                'Etna',
                'Italy',
                'Stratovolcano',
            ]
        ];
        $formatedVolcanoes = [
            [
                'name' => $volcanoes[0][0],
                'location' => $volcanoes[0][1],
                'type' => $volcanoes[0][2]
            ]
        ];

        $this->fileHandler
            ->expects($this->once())
            ->method('read')
            ->willReturn(json_encode($volcanoes));

        $result = $this->volcano->listVolcanoes();
        $this->assertEquals($result, $formatedVolcanoes);
    }

    public function testListVolcanoesWithWebResults(): void
    {
        $volcanoes = [
            [
                'Etna',
                'Italy',
                'Stratovolcano',
            ]
        ];
        $formatedVolcanoes = [
            1 => [
                'name' => $volcanoes[0][0],
                'location' => $volcanoes[0][1],
                'type' => $volcanoes[0][2],
            ]
        ];

        $html = '<div class="TableSearchResults">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subregion</th>
                            <th>Volcano Type</th>
                        </tr>
                    </thead>
                    <tr>
					    <td>Etna</td>
					    <td>Italy</td>
                        <td>Stratovolcano</td>
                    </tr>
                </table>
            </div>';

        $this->fileHandler
            ->expects($this->once())
            ->method('read')
            ->willReturn(false);

        $this->globalVolcanismService
            ->expects($this->once())
            ->method('getHoloceneVolcanoesList')
            ->willReturn($html);

        $result = $this->volcano->listVolcanoes();
        $this->assertEquals($result, $formatedVolcanoes);
    }

    public function testSaveVolcanoesInLocalFile(): void
    {

        $volcanoes = [
            [
                'Etna',
                'Italy',
                'Stratovolcano'
            ]
        ];
        $filename = __DIR__ . '/../../tests/data/volcanoes.json';

        $this->fileHandler
            ->expects($this->once())
            ->method('write')
            ->willReturn(file_put_contents($filename, json_encode($volcanoes)));

        $reflectedVolcano = new ReflectionClass($this->volcano);
        $saveVolcanoesInLocalFileMethod = $reflectedVolcano->getMethod('saveVolcanoesInLocalFile');
        $saveVolcanoesInLocalFileMethod->setAccessible(true);
        $saveVolcanoesInLocalFileMethod->invoke($this->volcano, $volcanoes);

        $result = file_get_contents($filename);
        $decodedResult = json_decode($result, true);
        $this->assertEquals($decodedResult, $volcanoes);
    }

    public function testGetFormatedVolcanoesWithLocalFile(): void
    {
        $volcanoes = [
            [
                'Etna',
                'Italy',
                'Stratovolcano',
            ]
        ];
        $formatedVolcanoes = [
            [
                'name' => $volcanoes[0][0],
                'location' => $volcanoes[0][1],
                'type' => $volcanoes[0][2]
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

    public function testGetFormatedVolcanoesWithWebResults(): void
    {
        $volcanoes = [
            [
                'Etna',
                'Italy',
                'Stratovolcano',
            ]
        ];
        $formatedVolcanoes = [
            1 => [
                'name' => $volcanoes[0][0],
                'location' => $volcanoes[0][1],
                'type' => $volcanoes[0][2],
            ]
        ];

        $html = '<div class="TableSearchResults">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subregion</th>
                            <th>Volcano Type</th>
                        </tr>
                    </thead>
                    <tr>
					    <td>Etna</td>
					    <td>Italy</td>
                        <td>Stratovolcano</td>
                    </tr>
                </table>
            </div>';

        $this->fileHandler
            ->expects($this->once())
            ->method('read')
            ->willReturn(false);

        $this->globalVolcanismService
            ->expects($this->once())
            ->method('getHoloceneVolcanoesList')
            ->willReturn($html);

        $reflectedVolcano = new ReflectionClass($this->volcano);
        $getFormatedVolcanoesMethod = $reflectedVolcano->getMethod('getFormatedVolcanoes');
        $getFormatedVolcanoesMethod->setAccessible(true);
        $result = $getFormatedVolcanoesMethod->invoke($this->volcano);

        $this->assertEquals($result, $formatedVolcanoes);
    }
}
