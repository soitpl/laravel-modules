<?php
/*
 * Module.php
 *
 * @lastModification 22.10.2020, 23:47
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Entity;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use soIT\LaravelModules\Files\Filesystem;
use soIT\LaravelModules\Providers\ServiceProvider;

class Module
{
    /**
     * @var string Module namespace
     */
    protected string $namespace = '';
    /**
     * @var string Module name
     */
    private string $name = '';
    /**
     * @var Filesystem
     */
    private Filesystem $fileSystem;

    /**
     * Module constructor.
     *
     * @param ServiceProvider $provider
     *
     * @throws ReflectionException
     * @codeCoverageIgnore
     */
    public function __construct(ServiceProvider $provider)
    {
        $this->init($provider);
    }

    /**
     * @return string Get module name
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNamespace():string
    {
        return $this->namespace;
    }

    /**
     * @return Filesystem Module filesystem class
     * @codeCoverageIgnore
     */
    public function getFilesystem():Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * Get module seeder class
     *
     * @param string $seederClassName
     *
     * @return Seeder|null
     * @codeCoverageIgnore
     */
    public function getSeeder(string $seederClassName):?Seeder
    {
        $file = $this->getSeederFile($seederClassName);

        if (file_exists($file)) {
            include_once $file;

            $className = $this->namespace.'\\'.$seederClassName;

            return new $className();
        }

        return null;
    }

    /**
     * @param ServiceProvider $provider
     *
     * @throws ReflectionException
     */
    public function init(ServiceProvider $provider):void
    {
        $this->setModuleName($provider);
        $this->setNamespace($provider);

        $this->fileSystem = $provider->getFileSystem();
    }

    /**
     * @param ServiceProvider $provider
     *
     * @return ReflectionClass
     * @throws ReflectionException
     * @codeCoverageIgnore
     */
    protected function reflectClass(ServiceProvider $provider):ReflectionClass
    {
        return new ReflectionClass($provider);
    }

    /**
     * Set module name from class name
     *
     * @param ServiceProvider $provider
     *
     * @throws ReflectionException
     */
    private function setModuleName(ServiceProvider $provider):void
    {
        if (!$this->name) {
            $this->name = Str::kebab(
                str_replace(['Module', 'Provider'], '', $this->reflectClass($provider)->getShortName())
            );
        }
    }

    /**
     * Set namespace without class name from class name
     *
     * @param ServiceProvider $provider
     *
     * @throws ReflectionException
     */
    private function setNamespace(ServiceProvider $provider):void
    {
        if (!$this->namespace) {
            $namespace = $this->reflectClass($provider)->getNamespaceName();

            $this->namespace = substr($namespace, 0, strrpos($namespace, '\\'));
        }
    }

    private function getSeederFile(string $seederClassName):string
    {
        $path = $this->fileSystem->getSeedersPath();
        $pathParts = explode('\\', $seederClassName);

        return $path.DIRECTORY_SEPARATOR.end($pathParts).'.php';
    }
}
