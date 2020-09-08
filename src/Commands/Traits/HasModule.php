<?php
/**
 * HasModule.php
 *
 * @lastModification 05.05.2020, 00:12
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands\Traits;

use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;
use soIT\LaravelModules\Repository\ModulesRepository;

trait HasModule
{
    /**
     * @var ModulesRepository
     */
    protected ModulesRepository $repository;

    /**
     * @param string $getModuleName
     *
     * @return Module
     */
    protected function getModule(string $getModuleName):Module
    {
        try {
            return $this->repository->get($getModuleName);
        } catch (ModuleNotRegisteredException $e) {
            $this->error($e->getMessage());
            exit();
        }
    }
}