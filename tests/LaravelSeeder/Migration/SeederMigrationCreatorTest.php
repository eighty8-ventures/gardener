<?php

namespace Eighty8\LaravelSeeder\Tests\Migration;


use Eighty8\LaravelSeeder\Migration\SeederMigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Mockery;
use Orchestra\Testbench\TestCase;

class SeederMigrationCreatorTest extends TestCase
{
    /** @var SeederMigrationCreator */
    private $seederMigrationCreator;

    /** @var SeederMigrationCreator|Mockery\MockInterface */
    private $mockSeederMigrationCreator;

    /** @var Filesystem|Mockery\MockInterface */
    private $files;

    /** @test */
    public function shouldCreate()
    {
        // Given
        $name = 'test';
        $path = __DIR__;

        // When
        $this->files
            ->expects()
            ->exists($path)
            ->andReturn(true);

        $this->files
            ->expects()
            ->get($this->seederMigrationCreator->stubPath() . DIRECTORY_SEPARATOR . SeederMigrationCreator::STUB_FILE)
            ->andReturn($this->seederMigrationCreator->stubPath() . SeederMigrationCreator::STUB_FILE);

        $this->files
            ->expects()
            ->put($path . '/' . date('Y_m_d_His') . '_' . Str::studly($name) . '.php',
                $this->seederMigrationCreator->stubPath() . SeederMigrationCreator::STUB_FILE)
            ->andReturn(1);

        $result = $this->seederMigrationCreator->create($name, $path, null, false);

        // Then
        $this->assertTrue(true);
        $this->assertNotNull($result);
    }

    /** @test */
    public function shouldThrowInvalidArgumentExceptionInEnsureMigrationDoesntAlreadyExist()
    {
        // Given
        $name = 'Mockery';
        $path = __DIR__;

        // When
        $this->expectException(\InvalidArgumentException::class);

        $this->seederMigrationCreator->create($name, $path, null, false);

        // Then
        // Exception expected above
    }

    /** @test */
    public function shouldReturnStubPath()
    {
        // Given

        // When
        $result = $this->seederMigrationCreator->stubPath();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals('/usr/local/src/src/LaravelSeeder/Migration/../../../stubs', $result);
    }

    /** @test */
    public function shouldEnsurePathExists()
    {
        // Given
        $name = 'test';
        $path = 'not-a-path';

        // When
        $this->files
            ->expects()
            ->exists($path)
            ->andReturn(false);

        $this->files
            ->expects()
            ->makeDirectory($path, 0755, true)
            ->andReturn(true);

        $this->files
            ->expects()
            ->get($this->seederMigrationCreator->stubPath() . DIRECTORY_SEPARATOR . SeederMigrationCreator::STUB_FILE)
            ->andReturn($this->seederMigrationCreator->stubPath() . SeederMigrationCreator::STUB_FILE);

        $this->files
            ->expects()
            ->put($path . '/' . date('Y_m_d_His') . '_' . Str::studly($name) . '.php',
                $this->seederMigrationCreator->stubPath() . SeederMigrationCreator::STUB_FILE)
            ->andReturn(1);

        $result = $this->seederMigrationCreator->create($name, $path, null, false);

        // Then
        $this->assertTrue(true);
        $this->assertNotNull($result);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->files = Mockery::mock(Filesystem::class);

        $this->mockSeederMigrationCreator = Mockery::mock(SeederMigrationCreator::class);

        $this->seederMigrationCreator = new SeederMigrationCreator($this->files);
    }
}