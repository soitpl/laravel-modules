<?php
/**
 * DatabaseCommandsServiceProvider.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Providers\Commands;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use soIT\LaravelModules\Commands\MigrateMakeCommand;
use soIT\LaravelModules\Commands\SeederMakeCommand;
use soIT\LaravelModules\Containers\ModulesContainer;

class DatabaseCommandsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'MigrateMake' => 'command.module.make.migrate',
        'SeederMake' => 'command.module.make.seeder',
    ];

    public function register()
    {
        $this->registerMakeMigrateCommand();
        $this->registerMakeSeederCommand();

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
                    $app['migration.creator'], $app['composer'], $this->makeModulesContainerInstance()
                );
            }
        );
    }

    /**
     *
     */
    private function registerMakeSeederCommand():void
    {
        $this->app->singleton(
            $this->commands['SeederMake'],
            function (Application $app) {
                return new SeederMakeCommand(
                    $app['files'], $app['composer'], $this->app->make(ModulesContainer::class)
                );
            }
        );
    }

    /**
     * @return ModulesContainer
     */
    private function makeModulesContainerInstance():ModulesContainer
    {
        return app(ModulesContainer::class);
    }
}