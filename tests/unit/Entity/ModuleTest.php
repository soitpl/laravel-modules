<?php
/**
 * ModuleTest.php
 *
 * @lastModification 04.05.2020, 07:41
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Tests\Unit\Entity;

use Faker\Provider\Lorem;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;
use soIT\LaravelModules\Providers\ServiceProvider;
use soIT\LaravelModules\Entity\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{

    public function testGetFilesystem()
    {
    }

    /**
     * @throws ReflectionException
     */
    public function testInit()
    {
        $nsWords = Lorem::words(5);

        $ns = implode('\\', $nsWords);
        $name = Lorem::word();

        /**
         * @var object|ServiceProvider|MockObject $serviceProviderMock ;
         */
        $serviceProviderMock = $this->getMockBuilder(ServiceProvider::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $reflectionClassMock = $this->getMockBuilder(\ReflectionClass::class)
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['getShortName', 'getNamespaceName'])
                                    ->getMock();

        $reflectionClassMock->expects($this->once())->method('getShortName')->willReturn($name.'Provider');
        $reflectionClassMock->expects($this->once())->method('getNamespaceName')->willReturn($ns);

        /**
         * @var Module|MockObject $instance
         */
        $instance = $this->getMockBuilder(Module::class)
                         ->disableOriginalConstructor()
                         ->onlyMethods(['reflectClass'])
                         ->disableOriginalConstructor()
                         ->getMock();

        $instance->expects($this->exactly(2))->method('reflectClass')->willReturn($reflectionClassMock);

        $instance->init($serviceProviderMock);

        $this->assertEquals($name, $instance->getName());
        $this->assertEquals(implode('\\', array_splice($nsWords, 0, 4)), $instance->getNamespace());
    }

    public function testGetSeeder()
    {
    }

    public function testGetName()
    {
    }
}
