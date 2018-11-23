<?php

namespace Eighty8\LaravelSeeder\Command;

use App;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @codeCoverageIgnore
 */
class SeedMake extends MigrateMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migratable seeder class';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'seed:make {name : The name of the seeder.}
        {--env= : The environment the seeder should be created for.}
        {--path= : The location where the seeder file should be created.}';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get the name of the seeder
        $name = trim($this->argument('name'));

        // Now we are ready to write the migration out to disk. Once we've written
        // the seeder out, we will dump-autoload for the entire framework to
        // make sure that the seeders are registered by the class loaders.
        $this->writeMigration($name, null, null);

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $model
     * @param string $table
     * @param bool   $created
     *
     * @return string
     */
    protected function writeMigration($model, $table, $created)
    {
        $env = $this->resolveEnvironment();

        $migration = $this->creator->create($model, $this->getOutputPath($env));

        $file = pathinfo($migration, PATHINFO_FILENAME);

        $this->line('<info>Created Seeder for '.ucfirst($env).' environment:</info>'." {$file}");

        return $file;
    }

    /**
     * Resolves the environment from input or from the Laravel application.
     *
     * @return string
     */
    protected function resolveEnvironment(): string
    {
        return ($this->input->getOption('env')) ?: App::environment();
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @param string $env
     *
     * @return string
     */
    protected function getOutputPath(string $env)
    {
        $targetPath = $this->input->getOption('path');

        $path = (empty($targetPath))
            ? database_path(config('seeders.dir'))
            : $this->laravel->basePath().DIRECTORY_SEPARATOR.$targetPath;

        return $path.DIRECTORY_SEPARATOR.$env;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the seeder.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['env', null, InputOption::VALUE_OPTIONAL, 'The environment to use for the seeders.'],
            ['path', null, InputOption::VALUE_OPTIONAL, 'The location where the seeder file should be created.'],
        ];
    }
}
