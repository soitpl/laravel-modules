<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright soIT 2019
 */

namespace soIT\LaravelModules\Tests\Unit\Files;

use Faker\Provider\Lorem;
use PHPUnit\Framework\TestCase;
use soIT\LaravelModules\Files\Filesystem;

class FilesystemTest extends TestCase
{
    public function testConstructor()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals($path, $fs->getBasePath());
    }

    public function testGetConfigPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals($path.DIRECTORY_SEPARATOR.Filesystem::CONFIG_DIR, $fs->getConfigPath());
    }

    public function testGetConfigPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['config' => $customDir]);
        $this->assertEquals($path.DIRECTORY_SEPARATOR.$customDir, $fs->getConfigPath());
    }

    public function testGetLangPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .Filesystem::LANG_DIR,
            $fs->getLangPath()
        );
    }

    public function testGetLangPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['lang' => $customDir]);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .$customDir,
            $fs->getLangPath()
        );
    }

    public function testGetLangPathWithCustomResourcesDir()
    {
        $customDir = Lorem::word();

        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['resources' => $customDir]);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .$customDir.DIRECTORY_SEPARATOR
            .Filesystem::LANG_DIR,
            $fs->getLangPath()
        );
    }

    public function testGetAssetsPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .Filesystem::ASSETS_DIR,
            $fs->getAssetsPath()
        );
    }

    public function testGetAssetsPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['assets' => $customDir]);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .$customDir,
            $fs->getAssetsPath()
        );
    }

    public function testGetViewPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .Filesystem::VIEWS_DIR,
            $fs->getViewsPath()
        );
    }

    public function testGetViewPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['views' => $customDir]);
        $this->assertEquals(
            $path.DIRECTORY_SEPARATOR
            .Filesystem::RESOURCE_DIR.DIRECTORY_SEPARATOR
            .$customDir,
            $fs->getViewsPath()
        );
    }

    public function testGetMigrationsPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals(
            $path
            .DIRECTORY_SEPARATOR
            .Filesystem::DATABASE_DIR
            .DIRECTORY_SEPARATOR
            .Filesystem::MIGRATIONS_DIR,
            $fs->getMigrationsPath()
        );
    }

    public function testGetMigrationsPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['migrations' => $customDir]);
        $this->assertEquals(
            $path
            .DIRECTORY_SEPARATOR
            .Filesystem::DATABASE_DIR
            .DIRECTORY_SEPARATOR
            .$customDir,
            $fs->getMigrationsPath()
        );
    }

    public function testGetMigrationsPathWithCustomDatabaseDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['database' => $customDir]);
        $this->assertEquals(
            $path
            .DIRECTORY_SEPARATOR
            .$customDir
            .DIRECTORY_SEPARATOR
            .Filesystem::MIGRATIONS_DIR,
            $fs->getMigrationsPath()
        );
    }

    public function testGetSeedersPath()
    {
        $path = $this->getFakePath();

        $fs = new Filesystem($path);
        $this->assertEquals(
            $path
            .DIRECTORY_SEPARATOR
            .Filesystem::DATABASE_DIR
            .DIRECTORY_SEPARATOR
            .Filesystem::SEEDERS_DIR,
            $fs->getSeedersPath()
        );
    }

    public function testGetSeedersPathWithCustomDir()
    {
        $customDir = Lorem::word();
        $path = $this->getFakePath();

        $fs = new Filesystem($path, ['seeders' => $customDir]);
        $this->assertEquals(
            $path
            .DIRECTORY_SEPARATOR
            .Filesystem::DATABASE_DIR
            .DIRECTORY_SEPARATOR
            .$customDir,
            $fs->getSeedersPath()
        );
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    private function getFakePath(string $separator = DIRECTORY_SEPARATOR):string
    {
        return implode($separator, Lorem::words(5));
    }
}
