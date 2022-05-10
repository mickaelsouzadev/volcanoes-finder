<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Mickaelsouzadev\VolcanoesFinder\Model\Volcano;
use Mickaelsouzadev\VolcanoesFinder\Service\GlobalVolcanismService;
use Mickaelsouzadev\VolcanoesFinder\Helper\FileHandler;

class VolcanoETest extends TestCase
{
    private Volcano $volcano;

    protected function setUp(): void
    {
        $this->volcano = new Volcano(
            new GlobalVolcanismService(),
            new Crawler(),
            new FileHandler()
        );
    }

    public function testGetHoloceneVolcanoes()
    {
        $result = $this->volcano->listVolcanoes();
        $this->assertIsArray($result);
    }
}
