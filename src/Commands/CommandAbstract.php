<?php
/**
 * CommandAbstract.php
 *
 * @lastModification 03.05.2020, 23:27
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands;

use Illuminate\Console\Command;
use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;
use soIT\LaravelModules\Repository\ModulesRepository;

abstract class CommandAbstract extends Command
{
    protected ModulesRepository $repository;

    /**
     * CommandAbstract constructor.
     *
     * @param ModulesRepository $repository
     */
    public function __construct(ModulesRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

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