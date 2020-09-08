<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT {2019}
 */

namespace soIT\LaravelModules\Files;

class Filesystem
{
    public const ASSETS_DIR = 'assets';
    public const CONFIG_DIR = 'Config';
    public const DATABASE_DIR = 'Database';
    public const LANG_DIR = 'lang';
    public const MIGRATIONS_DIR = 'Migrations';
    public const RESOURCE_DIR = 'resources';
    public const SEEDERS_DIR = 'Seeders';
    public const VIEWS_DIR = 'views';

    /**
     * @var string Base module path
     */
    private string $basePath;

    /**
     * @var array
     */
    private array $directories = [];

    /**
     * Filesystem constructor.
     *
     * @param string $path
     * @param array $directories
     */
    public function __construct(string $path, array $directories = [])
    {
        $this->setDirectories($directories);

        $this->setBasePath($path);
    }

    /**
     * Get path with translations files
     *
     * @return string
     */
    public function getConfigPath():string
    {
        return $this->path($this->basePath, $this->directories['config'] ?? self::CONFIG_DIR);
    }

    /**
     * Get path with translations files
     *
     * @return string
     */
    public function getLangPath():string
    {
        return $this->path($this->getResourcesPath(), $this->directories['lang'] ?? self::LANG_DIR);
    }

    /**
     * Get path with database migrations files
     * @return string
     */
    public function getMigrationsPath():string
    {
        return $this->path(
            $this->getDatabasePath(),
            $this->directories['migrations'] ?? self::MIGRATIONS_DIR
        );
    }

    /**
     * Get path with database seeders files
     * @return string
     */
    public function getSeedersPath():string
    {
        return $this->path(
            $this->getDatabasePath(),
            $this->directories['seeders'] ?? self::SEEDERS_DIR
        );
    }

    /**
     * Get path with blade views
     *
     * @return string
     */
    public function getAssetsPath():string
    {
        return $this->path($this->getResourcesPath(), $this->directories['assets'] ?? self::ASSETS_DIR);
    }

    /**
     * Get path with blade views
     *
     * @return string
     */
    public function getViewsPath():string
    {
        return $this->path($this->getResourcesPath(), $this->directories['views'] ?? self::VIEWS_DIR);
    }

    /**
     * @return string
     */
    public function getDatabasePath():string
    {
        return $this->path($this->basePath, $this->directories['database'] ?? self::DATABASE_DIR);
    }

    /**
     * @return string
     */
    public function getResourcesPath():string
    {
        return $this->path($this->basePath, $this->directories['resources'] ?? self::RESOURCE_DIR);
    }

    /**
     * @return string
     */
    public function getBasePath():string
    {
        return $this->basePath;
    }

    /**
     * Set base path for module
     *
     * @param string $path
     */
    public function setBasePath(string $path):void
    {
        $this->basePath = $path;
    }

    /**
     * @param array $directories
     */
    public function setDirectories(array $directories):void
    {
        $this->directories = $directories;
    }

    /**
     * @param string ...$args
     *
     * @return string
     */
    private function path(string ...$args):string
    {
        return implode(DIRECTORY_SEPARATOR, $args);
    }
}
