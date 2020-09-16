<?php

namespace Eighty8\LaravelSeeder;

use Eighty8\LaravelSeeder\Command\SeedInstall;
use Eighty8\LaravelSeeder\Command\SeedMake;
use Eighty8\LaravelSeeder\Command\SeedRefresh;
use Eighty8\LaravelSeeder\Command\SeedReset;
use Eighty8\LaravelSeeder\Command\SeedRollback;
use Eighty8\LaravelSeeder\Command\SeedRun;
use Eighty8\LaravelSeeder\Command\SeedStatus;
use Eighty8\LaravelSeeder\Migration\SeederMigrationCreator;
use Eighty8\LaravelSeeder\Migration\SeederMigrator;
use Eighty8\LaravelSeeder\Migration\SeederMigratorInterface;
use Eighty8\LaravelSeeder\Repository\SeederRepository;
use Eighty8\LaravelSeeder\Repository\SeederRepositoryInterface;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class SeederServiceProvider extends ServiceProvider
{
    const SEEDERS_CONFIG_PATH = __DIR__ . '/../../config/seeders.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boots the service provider.
     */
    public function boot(): void
    {
        $this->publishes([self::SEEDERS_CONFIG_PATH => base_path('config/seeders.php')]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SeedInstall::class,
                SeedMake::class,
                SeedRefresh::class,
                SeedReset::class,
                SeedRollback::class,
                SeedRun::class,
                SeedStatus::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(self::SEEDERS_CONFIG_PATH, 'seeders');

        $this->registerRepository();

        $this->registerMigrator();

        $this->registerCommands();
    }

    /**
     * Register the SeederRepository.
     */
    private function registerRepository(): void
    {
        $this->app->singleton(SeederRepository::class, function ($app) {
            return new SeederRepository($app['db'], config('seeders.table'));
        });

        $this->app->bind(SeederRepositoryInterface::class, function ($app) {
            return $app[SeederRepository::class];
        });
    }

    /**
     * Register the SeederMigrator.
     */
    private function registerMigrator(): void
    {
        $this->app->singleton(SeederMigrator::class, function ($app) {
            return new SeederMigrator($app[SeederRepositoryInterface::class], $app['db'], $app['files']);
        });

        $this->app->bind(SeederMigratorInterface::class, function ($app) {
            return $app[SeederMigrator::class];
        });

        $this->app->singleton(SeederMigrationCreator::class, function ($app) {
            return new SeederMigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }

    /**
     * Registers the Seeder Artisan commands.
     */
    private function registerCommands(): void
    {
        $this->app->bind(SeedInstall::class, function ($app) {
            return new SeedInstall($app[SeederRepositoryInterface::class]);
        });

        $this->app->bind(SeedMake::class, function ($app) {
            return new SeedMake($app[SeederMigrationCreator::class], $app[Composer::class]);
        });

        $this->app->bind(SeedRefresh::class, function () {
            return new SeedRefresh();
        });

        $this->app->bind(SeedReset::class, function ($app) {
            return new SeedReset($app[SeederMigrator::class]);
        });

        $this->app->bind(SeedRollback::class, function ($app) {
            return new SeedRollback($app[SeederMigrator::class]);
        });

        $this->app->bind(SeedRun::class, function ($app) {
            return new SeedRun($app[SeederMigrator::class]);
        });

        $this->app->bind(SeedStatus::class, function ($app) {
            return new SeedStatus($app[SeederMigrator::class]);
        });

        $this->commands([
            SeedInstall::class,
            SeedMake::class,
            SeedRefresh::class,
            SeedReset::class,
            SeedRollback::class,
            SeedRun::class,
            SeedStatus::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            SeederRepository::class,
            SeederRepositoryInterface::class,
            SeederMigrator::class,
            SeederMigratorInterface::class,
            SeederMigrationCreator::class,
            SeedInstall::class,
            SeedMake::class,
            SeedRefresh::class,
            SeedReset::class,
            SeedRollback::class,
            SeedRun::class,
            SeedStatus::class,
        ];
    }
}
