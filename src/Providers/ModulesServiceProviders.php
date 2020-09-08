<?php
/**
 * ModulesServiceProviders.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Providers\Commands\DatabaseCommandsServiceProvider;

class ModulesServiceProviders extends ServiceProvider
{
    /**
     * @var array Array of modules
     */
    private array $modules;

    /**
     *
     */
    public function boot():void
    {
        $this->createModuleRegistry();
        $this->registerCommands();
        $this->loadModulesConfig();
        $this->registerProviders();
    }

    /**
     * Load modules providers form config files
     */
    private function loadModulesConfig():void
    {
        $this->modules = $this->app['config']->get('modules');
    }

    /**
     * Register modules providers
     */
    private function registerProviders():void
    {
        array_map(
            function ($provider) {
                $this->app->register($provider);
            },
            $this->modules
        );
    }

    /**
     * Register custom commands
     */
    private function registerCommands()
    {
        $this->app->register(DatabaseCommandsServiceProvider::class);
    }

    /**
     *
     */
    private function createModuleRegistry()
    {
        $this->app->singleton(
            ModulesContainer::class,
            function () {
                return new ModulesContainer();
            }
        );
    }
}
