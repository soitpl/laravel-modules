<?php
/**
 * IsCreatesFile.php
 *
 * @lastModification 22.07.2020, 00:36
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT.pl 2018 - 2020
 * @url http://www.soit.pl
 */

namespace soIT\LaravelModules\Commands\Traits;

use Illuminate\Support\Facades\File;

trait IsCreatesFile
{
    /**
     * @param string $path
     */
    private function createDirectoryIfDontExists(string $path):void
    {
        if (!File::isDirectory($dir = dirname($path))) {
            File::makeDirectory($dir, 0777, true);
        }
    }
}