<?php
/**
 * MakeMigrationCommand.php
 *
 * @lastModification 03.05.2020, 17:27
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use soIT\LaravelModules\Commands\Traits\HasModule;
use soIT\LaravelModules\Repository\ModulesRepository;
use Symfony\Component\Console\Input\InputArgument;

class MigrateMakeCommand extends \Illuminate\Database\Console\Migrations\MigrateMakeCommand
{
    use HasModule;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:module-migration {module : The name of the module} {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make migration file for specified module';

    /**
     * CommandAbstract constructor.
     *
     * @param MigrationCreator $creator
     * @param Composer $composer
     * @param ModulesRepository $repository
     */
    public function __construct(MigrationCreator $creator, Composer $composer, ModulesRepository $repository)
    {
        parent::__construct($creator, $composer);

        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The migration name'],
            ['module', InputArgument::OPTIONAL, 'The module name'],
        ];
    }

    /**
     * @return string
     */
    protected function getMigrationPath():string
    {
        return $this->getModule($this->getModuleName())
                    ->getFilesystem()
                    ->getMigrationsPath();
    }

    /**
     * @return string
     */
    private function getModuleName():string
    {
        return $this->argument('module');
    }
}