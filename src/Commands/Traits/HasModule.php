<?php
/*
 * HasModule.php
 *
 * @lastModification 12.08.2020, 01:31
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands\Traits;

use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;

trait HasModule
{
    /**
     * @var ModulesContainer
     */
    protected ModulesContainer $repository;

    /**
     * @param string $moduleName
     *
     * @return Module
     */
    protected function getModule(string $moduleName):Module
    {
        try {
            return $this->getModuleFromRepository($moduleName);
        } catch (ModuleNotRegisteredException $e) {
            $this->error($e->getMessage());
            exit();
        }
    }

    /**
     *
     * @param string $moduleName
     *
     * @return Module|null
     * @throws ModuleNotRegisteredException
     */
    protected function getModuleFromRepository(string $moduleName):?Module
    {
        return $this->repository->get($moduleName);
    }
}