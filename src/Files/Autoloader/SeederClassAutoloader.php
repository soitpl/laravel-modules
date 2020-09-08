<?php
/**
 * SeederClassAutoloader.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Files\Autoloader;

use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Entity\Module;

class SeederClassAutoloader
{
    /**
     * @var ModulesContainer
     */
    private ModulesContainer $container;

    /**
     * SeederClassAutoloader constructor.
     *
     */
    public function __construct()
    {
        $this->container = $this->initContainer();
    }

    /**
     * @param $className
     */
    public static function loadClass($className)
    {
        (new self())->load($className);
    }

    private function load(string $className)
    {
        $className = $this->getSameClassName($className);

        if ($this->isSeeder($className)) {
            foreach ($this->container->all() as $module) {
                if ($path = $this->tryToLoadFromModule($className, $module)) {
                    return include_once $path;
                }
            }
        }

        return false;
    }

    private function initContainer():ModulesContainer
    {
        return app(ModulesContainer::class);
    }

    /**
     * Get class name without namespace
     *
     * @param string $className
     *
     * @return string
     */
    private function getSameClassName(string $className):string
    {
        return substr(strrchr($className, "\\"), 1);
    }

    /**
     * @param string $className
     * @param Module $module
     *
     * @return string|null
     */
    private function tryToLoadFromModule(string $className, Module $module):?string
    {
        $path = $this->getFullPath($module->getFilesystem()->getSeedersPath(), $className);

        return file_exists($path) ? $path : null;
    }

    /**
     * @param string $path
     * @param string $className
     *
     * @return string
     */
    private function getFullPath(string $path, string $className):string
    {
        return $path.DIRECTORY_SEPARATOR.$className.'.php';
    }

    /**
     * Check class name is has Seeder part
     *
     * @param string $className
     *
     * @return bool
     */
    private function isSeeder(string $className):bool
    {
        return strpos($className, 'Seeder') !== false;
    }
}