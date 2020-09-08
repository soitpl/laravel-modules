<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {2019}
 */
namespace soIT\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use soIT\LaravelModules\Providers\Commands\MigrationsCommandsServiceProvider;
use soIT\LaravelModules\Repository\ModulesRepository;

class ModulesServiceProviders extends ServiceProvider
{
    /**
     * @var array Array of modules
     */
    private array $modules;

    /**
     *
     */
    public function boot(): void
    {
        $this->createModuleRegistry();
        $this->registerCommands();
        $this->loadModulesConfig();
        $this->registerProviders();
    }

    /**
     * Load modules providers form config files
     */
    private function loadModulesConfig(): void
    {
        $this->modules = $this->app['config']->get('modules');
    }

    /**
     * Register modules providers
     */
    private function registerProviders(): void
    {
        array_map(function ($provider) {
            $this->app->register($provider);
        }, $this->modules);
    }

    /**
     * Register custom commands
     */
    private function registerCommands()
    {
        $this->app->register(MigrationsCommandsServiceProvider::class);
    }

    /**
     *
     */
    private function createModuleRegistry()
    {
        $this->app->singleton('soIT\LaravelModules\Repository\ModulesRepository', function ($app) {
            return new ModulesRepository();
        });
    }
}
