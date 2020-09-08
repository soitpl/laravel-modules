<?php
/**
 * CustomSeedCommand.php
 *
 * @lastModification 22.07.2020, 00:35
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Console;

use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Entity\Module;

class CustomSeedCommand extends SeedCommand
{
    private ModulesContainer $modulesRepository;

    /**
     * CustomSeedCommand constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param ModulesContainer $repository
     *
     * @codeCoverageIgnore
     */
    public function __construct(DatabaseManager $databaseManager, ModulesContainer $repository)
    {
        $this->modulesRepository = $repository;
        $this->initSeederAutoload();

        parent::__construct($databaseManager);
    }

    /**
     * Handle seed command
     *
     * @codeCoverageIgnore
     */
    public function handle():void
    {
        parent::handle();
        $this->seedModulesSeeders();
    }

    /**
     * Seed seeders from modules
     */
    public function seedModulesSeeders():void
    {
        $modules = $this->getModules();

        foreach ($modules as $module) {
            $this->invokeModuleSeeders($module);
        }

        $this->printInfo();
    }

    /**
     * Execute native class seeder
     *
     * @param Seeder $seeder
     *
     * @codeCoverageIgnore
     */
    protected function executeSeeder(Seeder $seeder):void
    {
        $seeder->setCommand($this);
        $seeder->__invoke();
    }

    /**
     * Get all modules from configuration
     *
     * @return array
     * @codeCoverageIgnore
     */
    protected function getModules():array
    {
        return $this->modulesRepository->all();
    }

    /**
     * Get seeder class name
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    protected function getClassName():string
    {
        return $this->input->getOption('class');
    }

    /**
     * Write seed info to command line
     *
     * @codeCoverageIgnore
     */
    protected function printInfo():void
    {
        $this->info('Modules database seeding completed successfully.');
    }

    /**
     * @param Module $module
     */
    private function invokeModuleSeeders(Module $module):void
    {
        $seeder = $module->getSeeder($this->getClassName());

        if ($seeder) {
            $this->executeSeeder($seeder);
        }
    }

    /**
     *
     */
    private function initSeederAutoload()
    {
        spl_autoload_register('soIT\LaravelModules\Files\Autoloader\SeederClassAutoloader::loadClass');
    }
}
