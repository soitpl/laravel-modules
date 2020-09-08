<?php
/**
 * ServiceProvider.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Providers;

use Illuminate\Foundation\Application;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Files\Filesystem;

abstract class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var Filesystem Filesystem class
     */
    protected Filesystem $fileSystem;

    /**
     * @var Module
     */
    protected Module $module;

    /**
     * @var ModulesContainer
     */
    protected ModulesContainer $container;

    /**
     * ServiceProvider constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->container = $app->make(ModulesContainer::class);

        $this->fileSystem = $this->initFileSystem();
    }

    abstract protected function initFileSystem():Filesystem;

    /**
     * @throws ReflectionException
     */
    public function boot()
    {
        $this->registerModuleInRepository();

        $this->publishConfigs();
        $this->mergeMainConfig();
        $this->registerMigrations();
        $this->registerResources();
    }

    /**
     * Get module path
     *
     * @return Filesystem
     */
    public function getFileSystem():Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * Register all module resources: views, trans
     * @codeCoverageIgnore
     */
    protected function registerResources():void
    {
        $this->registerViews();
        $this->registerTranslations();
    }

    /**
     * Register translations files
     * @codeCoverageIgnore
     */
    protected function registerTranslations():void
    {
        $this->loadTranslationsFrom($this->fileSystem->getLangPath(), $this->module->getName());
    }

    /**
     * Register views files
     * @codeCoverageIgnore
     */
    protected function registerViews():void
    {
        $this->loadViewsFrom($this->fileSystem->getViewsPath(), $this->module->getName());
    }

    /**
     * Register directory with migrations files
     * @codeCoverageIgnore
     */
    private function registerMigrations():void
    {
        $this->loadMigrationsFrom($this->fileSystem->getMigrationsPath());
    }

    /**
     * Register module instance in repository
     * @throws ReflectionException
     */
    private function registerModuleInRepository():void
    {
        $this->module = new Module($this);

        $this->container->register($this->module);
    }

    /**
     * Publish configs files
     */
    private function publishConfigs():void
    {
        $configPath = $this->fileSystem->getConfigPath();

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));
        $moduleName = $this->module->getName();

        $configFiles = [];

        foreach ($iterator as $file) {
            $path = $file->getPathname();

            if ($file->isDir()) {
                continue;
            }

            $configFiles[$path] = config_path($moduleName.str_replace($configPath, '', $file->getPathname()));
        }

        $this->publishes($configFiles, 'config');
    }

    /**
     * Merge main config file
     */
    private function mergeMainConfig():void
    {
        $filePath = $this->fileSystem->getConfigPath().DIRECTORY_SEPARATOR.'Config.php';

        if (file_exists($filePath)) {
            $this->mergeConfigFrom($filePath, $this->module->getName());
        }
    }
}
