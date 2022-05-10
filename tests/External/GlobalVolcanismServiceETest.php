<?php

use PHPUnit\Framework\TestCase;
use Mickaelsouzadev\VolcanoesFinder\Service\GlobalVolcanismService;

class GlobalVolcanismServiceETest extends TestCase
{
    private GlobalVolcanismService $service;

    protected function setUp(): void
    {
        $this->service = new GlobalVolcanismService();
    }

    public function testGetHoloceneVolcanoes()
    {
        $result = $this->service->getHoloceneVolcanoesList();
        $this->assertIsString($result);
        $this->assertStringStartsWith('<!DOCTYPE html>', $result);
    }
}
