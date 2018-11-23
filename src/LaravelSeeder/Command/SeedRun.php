<?php

namespace Eighty8\LaravelSeeder\Command;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

/**
 * @codeCoverageIgnore
 */
class SeedRun extends AbstractSeedMigratorCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database seeders';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        // Prepare the migrator.
        $this->prepareMigrator();

        // Execute the migrator.
        $this->info('Seeding data for '.ucfirst($this->getEnvironment()).' environment...');
        $this->migrator->run($this->getMigrationPaths(), $this->getMigrationOptions());

        $this->info('Seeded data for '.ucfirst($this->getEnvironment()).' environment');
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}
