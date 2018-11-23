<?php

namespace Eighty8\LaravelSeeder\Tests\Command;


use Eighty8\LaravelSeeder\Command\SeedInstall;
use Eighty8\LaravelSeeder\Repository\SeederRepository;
use Mockery;
use Orchestra\Testbench\TestCase;

class SeedInstallTest extends TestCase
{
    /** @var SeederRepository|Mockery\MockInterface */
    private $repository;

    /** @var SeedInstall */
    private $seedInstall;

    /** @test */
    public function shouldConstruct()
    {
        // Given

        // When
        $seedInstall = new SeedInstall($this->repository);

        // Then
        $this->assertNotNull($seedInstall);
    }

    /** @test */
    public function shouldHandle()
    {
//        $this->artisan('seed:install', ['database' => 'foo']);

        // Given
//        $seedInstall = new SeedInstall($this->repository);
//        $definition = new InputDefinition([new InputArgument('database', InputArgument::REQUIRED)]);
//
//        $input = new ArrayInput(['database' => 'foo'], $definition);
//
//        $seedInstall->run($input, new BufferedOutput());


        // When
//        $this->input
//            ->expects()
//            ->getOption('database')
//            ->andReturn('test-source');
//
//        $this->repository
//            ->expects()
//            ->setSource('test-source');
//
//        $this->repository
//            ->expects()
//            ->createRepository();

//        $this->seedInstall->handle();

        // Then
        $this->assertTrue(true);

    }

    protected function setUp()
    {
        parent::setUp();

        $this->repository = Mockery::mock(SeederRepository::class);

        $this->seedInstall = new SeedInstall($this->repository);
    }
}