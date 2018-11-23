<?php

namespace Eighty8\LaravelSeeder\Command;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

/**
 * @codeCoverageIgnore
 */
class SeedRollback extends AbstractSeedMigratorCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback all database seeders';

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

        // Rolls back the migrator.
        $this->info('Rolling back seeded data for '.ucfirst($this->getEnvironment()).' environment...');
        $this->migrator->rollback($this->getMigrationPaths(), $this->getMigrationOptions());

        $this->info('Rolled back seeded data for '.ucfirst($this->getEnvironment()).' environment');
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
