<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelModules\Repository;

use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;
use soIT\LaravelModules\Entity\Module;

class ModulesRepository
{
    /**
     * @var Module[] Array of module class instances
     */
    protected array $modules;

    /**
     * @param string $name
     *
     * @return Module
     * @throws ModuleNotRegisteredException
     */
    public function get(string $name):Module
    {
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        } else {
            throw new ModuleNotRegisteredException("Module {$name} wasn'r found in register modules");
        }
    }

    /**
     * Register module instance
     *
     * @param Module $module
     */
    public function register(Module $module)
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
