<?php
/*
 * ModulesContainer.php
 *
 * @lastModification 12.08.2020, 01:31
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Containers;

use Psr\Container\ContainerInterface;
use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;

class ModulesContainer implements ContainerInterface
{
    /**
     * @var Module[] Array of module class instances
     */
    protected array $modules = [];

    /**
     * @param string $id
     *
     * @return Module
     * @throws ModuleNotRegisteredException
     */
    public function get($id):Module
    {
        if (isset($this->modules[$id])) {
            return $this->modules[$id];
        } else {
            throw new ModuleNotRegisteredException("Module {$id} was not found in register modules");
        }
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id):bool
    {
        return array_key_exists($id, $this->modules);
    }

    /**
     * @return int
     */
    public function count():int
    {
        return count($this->modules);
    }

    /**
     * Register module instance
     *
     * @param Module $module
     */
    public function register(Module $module):void
    {
        $this->modules[$module->getName()] = $module;
    }

    /**
     * @return array|Module[]
     */
    public function all():array
    {
        return $this->modules;
    }
}
