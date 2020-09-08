<?php
/**
 * SeederMakeCommand.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use soIT\LaravelModules\Commands\Traits\HasModule;
use soIT\LaravelModules\Containers\ModulesContainer;
use Symfony\Component\Console\Input\InputArgument;

class SeederMakeCommand extends \Illuminate\Database\Console\Seeds\SeederMakeCommand
{
    use HasModule;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:seeder';

    /**
     * CommandAbstract constructor.
     *
     * @param Filesystem $files
     * @param Composer $composer
     * @param ModulesContainer $repository
     *
     * @codeCoverageIgnore
     */
    public function __construct(Filesystem $files, Composer $composer, ModulesContainer $repository)
    {
        parent::__construct($files, $composer);

        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module name'],
            ['name', InputArgument::REQUIRED, 'The seeder name'],
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function getPath($name):string
    {
        return $this->getModule($this->getModuleName())
                    ->getFilesystem()
                    ->getSeedersPath().'/'.$name.'.php';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    private function getModuleName():string
    {
        return $this->argument('module');
    }
}