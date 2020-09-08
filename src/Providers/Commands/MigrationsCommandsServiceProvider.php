<?php
/**
 * CommandsServiceProvider.php
 *
 * @lastModification 04.05.2020, 23:32
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Providers\Commands;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use soIT\LaravelModules\Commands\MigrateMakeCommand;
use soIT\LaravelModules\Repository\ModulesRepository;

class MigrationsCommandsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'MigrateMake' => 'command.module.make.migrate',
    ];

    public function register()
    {
        $this->registerMakeMigrateCommand();

        $this->commands(array_values($this->commands));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides():array
    {
        return [array_values($this->commands)];
    }

    /**
     *
     */
    private function registerMakeMigrateCommand():void
    {
        $this->app->singleton(
            $this->commands['MigrateMake'],
            function (Application $app) {
                return new MigrateMakeCommand(
                    $app['migration.creator'], $app['composer'], $app->make(ModulesRepository::class)
                );
            }
        );
    }

}