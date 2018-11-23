<?php

namespace Eighty8\LaravelSeeder\Tests;


use Eighty8\LaravelSeeder\SeederServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class SeederServiceProviderTest extends TestCase
{
    /** @test */
    public function shouldBoot()
    {
        // Given
        $seederServiceProvider = new SeederServiceProvider(new Application());

        // When
        $seederServiceProvider->boot();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldProvides()
    {
        // Given
        $seederServiceProvider = new SeederServiceProvider(new Application());

        // When
        $result = $seederServiceProvider->provides();

        // Then
        $this->assertNotNull($result);
    }
}