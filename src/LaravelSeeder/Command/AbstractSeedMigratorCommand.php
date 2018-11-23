<?php

namespace Eighty8\LaravelSeeder\Command;

use Eighty8\LaravelSeeder\Migration\SeederMigratorInterface;
use Illuminate\Console\Command;

abstract class AbstractSeedMigratorCommand extends Command
{
    /* Constant for all environments */
    const ALL_ENVIRONMENTS = 'all';

    /** @var string */
    protected $environment;

    /** @var SeederMigratorInterface */
    protected $migrator;

    /** @var array */
    protected $migrationOptions = [];

    /** @var array */
    protected $migrationPaths = [];

    /**
     * Constructor.
     *
     * @param SeederMigratorInterface $migrator
     */
    public function __construct(SeederMigratorInterface $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * Prepares the migrator for usage.
     *
     * @codeCoverageIgnore
     */
    protected function prepareMigrator(): void
    {
        $this->connectToRepository();
        $this->resolveEnvironment();
        $this->resolveMigrationPaths();
        $this->resolveMigrationOptions();
    }

    /**
     * Prepares the repository for usage.
     *
     * @codeCoverageIgnore
     */
    protected function connectToRepository(): void
    {
        $database = $this->input->getOption('database');

        $this->getMigrator()->setConnection($database);

        if (!$this->getMigrator()->repositoryExists()) {
            $this->call('seed:install', ['--database' => $database]);
        }
    }

    /**
     * Gets the migrator instance.
     *
     * @return SeederMigratorInterface
     */
    public function getMigrator(): SeederMigratorInterface
    {
        return $this->migrator;
    }

    /**
     * Sets the migrator instance.
     *
     * @param SeederMigratorInterface $migrator
     */
    public function setMigrator(SeederMigratorInterface $migrator)
    {
        $this->migrator = $migrator;
    }

    /**
     * Sets up the environment for the migrator.
     *
     * @codeCoverageIgnore
     */
    protected function resolveEnvironment(): void
    {
        $env = $this->input->getOption('env') ?: $this->getLaravel()->environment();

        $this->setEnvironment($env);

        $this->getMigrator()->setEnvironment($this->getEnvironment());
    }

    /**
     * Gets the environment.
     *
     * @return string
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * Sets the environment.
     *
     * @param string $env
     */
    public function setEnvironment(string $env): void
    {
        $this->environment = $env;
    }

    /**
     * Resolves the paths for the migration files to run the migrator against.
     *
     * @codeCoverageIgnore
     */
    protected function resolveMigrationPaths(): void
    {
        $pathFromConfig = database_path(config('seeders.dir'));

        // Add the 'all' environment path to migration paths
        $allEnvPath = $pathFromConfig.DIRECTORY_SEPARATOR.self::ALL_ENVIRONMENTS;
        $this->addMigrationPath($allEnvPath);

        // Add the targeted environment path to migration paths
        $pathWithEnv = $pathFromConfig.DIRECTORY_SEPARATOR.$this->getEnvironment();
        $this->addMigrationPath($pathWithEnv);
    }

    /**
     * Appends a migration path to the list of paths.
     *
     * @param string $path
     */
    public function addMigrationPath(string $path): void
    {
        $this->migrationPaths[] = $path;
    }

    /**
     * Resolves the options for the migrator.
     *
     * @codeCoverageIgnore
     */
    protected function resolveMigrationOptions(): void
    {
        $pretend = $this->input->getOption('pretend');

        if ($pretend) {
            $this->addMigrationOption('pretend', $pretend);
        }
    }

    /**
     * Adds an option to the list of migration options.
     *
     * @param string $key
     * @param string $value
     */
    public function addMigrationOption(string $key, string $value): void
    {
        $this->migrationOptions[$key] = $value;
    }

    /**
     * Execute the console command.
     */
    abstract public function handle(): void;

    /**
     * Gets the paths for the migration files to run the migrator against.
     *
     * @return array
     */
    public function getMigrationPaths(): array
    {
        return $this->migrationPaths;
    }

    /**
     * Sets the paths for the migration files to run the migrator against.
     *
     * @param array $paths
     */
    public function setMigrationPaths(array $paths): void
    {
        $this->migrationPaths = $paths;
    }

    /**
     * Gets the options for the migrator.
     *
     * @return array
     */
    public function getMigrationOptions(): array
    {
        return $this->migrationOptions;
    }

    /**
     * Sets the options for the migrator.
     *
     * @param array $migrationOptions
     */
    public function setMigrationOptions(array $migrationOptions)
    {
        $this->migrationOptions = $migrationOptions;
    }
}
