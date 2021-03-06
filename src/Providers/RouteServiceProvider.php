<?php
/**
 * RouteServiceProvider.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionException;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the admin modules
     *
     * @return void
     * @throws ReflectionException
     */
    public function map():void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the module
     *
     * @return void
     * @throws ReflectionException
     */
    protected function mapWebRoutes()
    {
        $file = $this->getRoutesDir().'/web.php';

        if (file_exists($file)) {
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group($file);
        }
    }

    /**
     * Define the "api" routes for the application
     *
     * @return void
     * @throws ReflectionException
     */
    protected function mapApiRoutes()
    {
        $file = $this->getRoutesDir().'/api.php';

        if (file_exists($file)) {
            Route::prefix('api')
                 ->middleware('api')
                 ->namespace($this->namespace)
                 ->group($file);
        }
    }

    /**
     * Get modules route directory
     *
     * @throws ReflectionException
     */
    protected function getRoutesDir():string
    {
        return realpath(
            dirname((new ReflectionClass($this))->getFileName()).'/../../routes'
        );
    }
}
