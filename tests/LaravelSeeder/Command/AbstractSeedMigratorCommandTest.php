<?php

namespace Eighty8\LaravelSeeder\Tests\Command;


use Eighty8\LaravelSeeder\Command\AbstractSeedMigratorCommand;
use Eighty8\LaravelSeeder\Migration\SeederMigratorInterface;
use Mockery;
use Orchestra\Testbench\TestCase;

class AbstractSeedMigratorCommandTest extends TestCase
{
    /** @var SeederMigratorInterface|Mockery\MockInterface */
    private $seederMigratorInterface;

    /** @var SomeAbstractSeedMigratorCommand */
    private $someAbstractSeedMigratorCommand;

    /** @test */
    public function shouldConstruct()
    {
        // Given

        // When
        $this->someAbstractSeedMigratorCommand = new SomeAbstractSeedMigratorCommand($this->seederMigratorInterface);

        // Then
        $this->assertNotNull($this->someAbstractSeedMigratorCommand);
    }

    /** @test */
    public function shouldSetAndGetMigrator()
    {
        // Given

        // When
        $this->someAbstractSeedMigratorCommand->setMigrator($this->seederMigratorInterface);

        $result = $this->someAbstractSeedMigratorCommand->getMigrator();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($this->seederMigratorInterface, $result);
    }

    /** @test */
    public function shouldSetAndGetEnvironment()
    {
        // Given
        $env = 'test';

        // When
        $this->someAbstractSeedMigratorCommand->setEnvironment($env);

        $result = $this->someAbstractSeedMigratorCommand->getEnvironment();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($env, $result);
    }

    /** @test */
    public function shouldAddMigrationPath()
    {
        // Given
        $path = 'path';

        // When
        $this->someAbstractSeedMigratorCommand->addMigrationPath($path);

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldAddMigrationOptions()
    {
        // Given
        $key = 'test-key';
        $value = 'test-value';
        $compare = [
            'test-key' => 'test-value'
        ];

        // When
        $this->someAbstractSeedMigratorCommand->addMigrationOption($key, $value);

        $result = $this->someAbstractSeedMigratorCommand->getMigrationOptions();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($compare, $result);
    }

    /** @test */
    public function shouldSetAndGetMigrationPaths()
    {
        // Given
        $path = ['test'];

        // When
        $this->someAbstractSeedMigratorCommand->setMigrationPaths($path);

        $result = $this->someAbstractSeedMigratorCommand->getMigrationPaths();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($path, $result);
    }

    /** @test */
    public function shouldSetAndGetMigrationOptions()
    {
        // Given
        $options = ['test'];

        // When
        $this->someAbstractSeedMigratorCommand->setMigrationOptions($options);

        $result = $this->someAbstractSeedMigratorCommand->getMigrationOptions();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($options, $result);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->seederMigratorInterface = Mockery::mock(SeederMigratorInterface::class);

        $this->someAbstractSeedMigratorCommand = new SomeAbstractSeedMigratorCommand($this->seederMigratorInterface);
    }
}


class SomeAbstractSeedMigratorCommand extends AbstractSeedMigratorCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // TODO: Implement handle() method.
    }
}