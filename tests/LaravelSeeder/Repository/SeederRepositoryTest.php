<?php

namespace Eighty8\LaravelSeeder\Tests\Repository;


use Eighty8\LaravelSeeder\Repository\SeederRepository;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder as Builder2;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Mockery;
use Orchestra\Testbench\TestCase;

class SeederRepositoryTest extends TestCase
{
    /** @var ConnectionResolver|Mockery\MockInterface */
    private $connectionResolver;

    /** @var string */
    private $table = 'test-table';

    /** @var SeederRepository */
    private $seederRepository;

    /** @var SeederRepository|Mockery\MockInterface */
    private $mockSeederRepository;

    /** @var Connection|Mockery\MockInterface */
    private $connection;

    /** @var Builder|Mockery\MockInterface */
    private $builder;

    /** @var Builder2|Mockery\MockInterface */
    private $schemaBuilder;

    /** @var Collection|Mockery\MockInterface */
    private $collection;

    /** @var Blueprint|Mockery\MockInterface */
    private $blueprint;

    /** @test */
    public function shouldConstruct()
    {
        // Given
        $this->table = 'test-table';

        // When
        $result = $this->seederRepository = new SeederRepository($this->connectionResolver, $this->table);

        // Then
        $this->assertNotNull($result);
    }

    /** @test */
    public function shouldGetRan()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection($name)
            ->andReturn($this->connection);

        $this->connection
            ->expects()
            ->table($this->table)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->where('env', '=', $this->seederRepository->getEnvironment())
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->pluck('seed')
            ->andReturn($this->collection);

        $this->collection
            ->expects()
            ->toArray()
            ->andReturn(['test']);

        $result = $this->seederRepository->getRan();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldGetConnection()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection('source')
            ->andReturn(new Connection('testpdo', 'test', 'test', ['test']));

        $result = $this->seederRepository->getConnection();

        // Then
        $this->assertNotNull($result);
    }

    /** @test */
    public function shouldCheckHasEnvironment()
    {
        // Given
        $env = 'test-env';

        // When
        $result1 = $this->seederRepository->hasEnvironment();

        $this->seederRepository->setEnvironment($env);
        $result2 = $this->seederRepository->hasEnvironment();

        // Then
        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }

    /** @test */
    public function shouldSetAndGetEnvironment()
    {
        // Given
        $env = 'test-env';

        // When
        $this->seederRepository->setEnvironment($env);
        $result = $this->seederRepository->getEnvironment();

        // Then
        $this->assertNotNull($result);
        $this->assertEquals($env, $result);
    }

    /** @test */
    public function shouldGetLast()
    {
        // Given
        $name = 'test';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection($name)
            ->andReturn($this->connection);

        $this->connection
            ->expects()
            ->table($this->table)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->where('env', '=', $this->seederRepository->getEnvironment())
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->max('batch')
            ->andReturn(1);

        $this->builder
            ->expects()
            ->where('batch', 1)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->orderBy('seed', 'desc')
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->get()
            ->andReturn($this->collection);

        $this->collection
            ->expects()
            ->toArray()
            ->andReturn(['test']);

        $result = $this->seederRepository->getLast();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldLog()
    {
        // Given
        $name = 'source';
        $file = 'file';
        $batch = 1;

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection($name)
            ->andReturn($this->connection);

        $this->connection
            ->expects()
            ->table($this->table)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->insert([
                'seed' => $file,
                'env' => $this->seederRepository->getEnvironment(),
                'batch' => $batch,
            ])
            ->andReturn($this->builder);

        $result = $this->seederRepository->log($file, $batch);

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldDelete()
    {
        // Given
        $name = 'test';
        $seeder = new Seed();
        $seeder->seed = 1;

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection($name)
            ->andReturn($this->connection);

        $this->connection
            ->expects()
            ->table($this->table)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->where('env', '=', $this->seederRepository->getEnvironment())
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->where('seed', 1)
            ->andReturn($this->builder);

        $this->builder
            ->expects()
            ->delete();

        $result = $this->seederRepository->delete($seeder);

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldGetNextBatchNumber()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection('source')
            ->andReturn(new Connection('testpdo', 'test', 'test', ['test']));

        $result = $this->seederRepository->getNextBatchNumber();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldCreateRepository()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection($name)
            ->andReturn($this->connection);

        $this->connection
            ->expects()
            ->getSchemaBuilder()
            ->andReturn($this->schemaBuilder);

        $this->schemaBuilder
            ->expects()
            ->create('test-table', function (Blueprint $table) {
                new Fluent(['test' => 'test']);
                new Fluent(['test' => 'test']);
                new Fluent(['test' => 'test']);
            });

        $result = $this->seederRepository->createRepository();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldCheckRepositoryExists()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection('source')
            ->andReturn(new Connection('testpdo', 'test', 'test', ['test']));

        $result = $this->seederRepository->repositoryExists();

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldSetSource()
    {
        // Given
        $name = 'source';

        // When
        $result = $this->seederRepository->setSource($name);

        // Then <- umm is there a better assert
        $this->assertNull($result);
    }

    /** @test */
    public function shouldGetMigrations()
    {
        // Given
        $name = 'source';
        $steps = 1;

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection('source')
            ->andReturn(new Connection('testpdo', 'test', 'test', ['test']));

        $result = $this->seederRepository->getMigrations($steps);

        // Then
        $this->assertTrue(true);
    }

    /** @test */
    public function shouldGetMigrationBatches()
    {
        // Given
        $name = 'source';

        // When
        $this->seederRepository->setSource($name);

        $this->connectionResolver
            ->expects()
            ->connection('source')
            ->andReturn(new Connection('testpdo', 'test', 'test', ['test']));

        $result = $this->seederRepository->getMigrationBatches();

        // Then
        $this->assertTrue(true);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->connectionResolver = Mockery::mock(ConnectionResolver::class);

        $this->seederRepository = new SeederRepository($this->connectionResolver, $this->table);

        $this->mockSeederRepository = Mockery::mock(SeederRepository::class);

        $this->connection = Mockery::mock(Connection::class);

        $this->builder = Mockery::mock(Builder::class);

        $this->schemaBuilder = Mockery::mock(Builder2::class);

        $this->collection = Mockery::mock(Collection::class);

        $this->blueprint = Mockery::mock(Blueprint::class);
    }
}

class Seed
{
    public $seed;

}