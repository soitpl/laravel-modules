<?php
/**
 * ModulesContainerTest.php
 *
 * @lastModification 22.07.2020, 00:35
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Tests\Unit\Containers;

use Faker\Factory;
use Faker\Provider\Base;
use PHPUnit\Framework\TestCase;
use soIT\LaravelModules\Containers\ModulesContainer;
use soIT\LaravelModules\Entity\Module;
use soIT\LaravelModules\Exceptions\ModuleNotRegisteredException;

class ModulesContainerTest extends TestCase
{
    public function testRegister()
    {
        $no = Base::numberBetween(1, 5);
        $words = Factory::create()->unique()->words($no);

        $container = $this->mockContainer($no, $words);

        $this->assertTrue($container->has($words[1]));
        $this->assertEquals($no, $container->count());
    }

    public function testAll()
    {
        $no = Base::numberBetween(1, 5);
        $words = Factory::create()->unique()->words($no);

        $container = $this->mockContainer($no, $words);

        $all = $container->all();

        $this->assertCount($no, $all);
    }

    /**
     * @throws ModuleNotRegisteredException
     */
    public function testGet()
    {
        $no = Base::numberBetween(1, 5);
        $words = Factory::create()->unique()->words($no);

        $container = $this->mockContainer($no, $words);

        $module = $container->get($words[1]);

        $this->assertInstanceOf(Module::class, $module);
    }

    /**
     * @throws ModuleNotRegisteredException
     */
    public function testGetWithNonExistsModule()
    {
        $no = Base::numberBetween(1, 5);
        $words = Factory::create()->unique()->words($no);

        $container = $this->mockContainer($no, $words);

        $this->expectException(ModuleNotRegisteredException::class);
        $container->get('xxxxxx');
    }

    /**
     * @param int $no
     *
     * @param array $words
     *
     * @return ModulesContainer
     */
    private function mockContainer(int $no, array $words):ModulesContainer
    {
        $container = new ModulesContainer();

        for ($i = 0; $i < $no; $i++) {
            $module = $this->createStub(Module::class);
            $module->method('getName')->willReturn($words[$i]);

            $container->register($module);
        }

        return $container;
    }
}
