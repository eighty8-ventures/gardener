<?php

namespace Eighty8\LaravelSeeder\Tests\Migration;


use Eighty8\LaravelSeeder\Migration\MigratableSeeder;
use Eighty8\LaravelSeeder\Migration\SeederMigrator;
use Eighty8\LaravelSeeder\Repository\SeederRepositoryInterface;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use Orchestra\Testbench\TestCase;

class SeederMigratorTest extends TestCase
{
    /** @var SeederRepositoryInterface|Mockery\MockInterface */
    private $repository;

    /** @var ConnectionResolverInterface|Mockery\MockInterface */
    private $resolver;

    /** @var Filesystem|Mockery\MockInterface */
    private $files;

    /** @var SeederMigrator */
    private $seederMigrator;

    /** @var SeederMigrator|Mockery\MockInterface */
    private $mockSeederMigrator;

    /** @test */
    public function shouldConstruct()
    {
        // Given

        // When
        $this->seederMigrator = new SeederMigrator($this->repository, $this->resolver, $this->files);

        // Then
        $this->assertNotNull($this->seederMigrator);
    }

    /** @test */
    public function shouldSetEnvironment()
    {
        // Given
        $env = 'test';

        // When
        $this->repository
            ->expects()
            ->setEnvironment($env);

        $this->seederMigrator->setEnvironment($env);

        // Then
    }

    /** @test */
    public function shouldCheckHasEnvironment()
    {
        // Given

        // When
        $this->repository
            ->expects()
            ->hasEnvironment()
            ->andReturn(true);

        $this->seederMigrator->hasEnvironment();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldGetEnvironment()
    {
        // Given
        $env = 'test';

        // When
        $this->repository
            ->expects()
            ->getEnvironment()
            ->andReturn('test');

        $result = $this->seederMigrator->getEnvironment();

        // Then
        $this->assertNotNull($result);
    }

    /** @test */
    public function shouldResolve()
    {
        // Given
        $file = '2017_01_01_000000_CreateUserPermissionTable';

        // When
        $this->mockSeederMigrator
            ->expects()
            ->resolve($file)
            ->andReturn(new SomeMigratableSeeder());

        $result = $this->seederMigrator->resolve($file);

        // Then
        $this->assertNotNull($result);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->repository = Mockery::mock(SeederRepositoryInterface::class);

        $this->resolver = Mockery::mock(ConnectionResolverInterface::class);

        $this->files = Mockery::mock(Filesystem::class);

        $this->seederMigrator = new SeederMigrator($this->repository, $this->resolver, $this->files);

        $this->mockSeederMigrator = Mockery::mock(SeederMigrator::class);
    }
}

class SomeMigratableSeeder extends MigratableSeeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // TODO: Implement run() method.
    }

    /**
     * Reverses the database seeder.
     */
    public function down(): void
    {
        // TODO: Implement down() method.
    }
}