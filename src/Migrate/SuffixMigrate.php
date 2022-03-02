<?php

namespace Fatihirday\Suffixed\Migrate;

use \Illuminate\Filesystem\Filesystem;
use \Illuminate\Support\Str;

class SuffixMigrate
{
    protected $suffix = null;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function createSuffixedTables(string $suffixCode)
    {
        $app = (new self);

        $app->setSuffix($suffixCode);

        foreach (glob(database_path('migrations') . '/*') as $migrationFile) {
            $app->createTables($migrationFile);
        }
    }

    /**
     * @param string $suffixCode
     * @throws \Exception
     */
    protected function setSuffix(string $suffixCode)
    {
        if (config('suffix_auto_check', true) && !$this->checkSuffixCode($suffixCode)) {
            throw new \Exception('Not Suffixed : ' . $suffixCode);
        }

        $this->suffix = $suffixCode;
    }

    protected function checkSuffixCode(string $suffixCode): bool
    {
        $suffixTable = config('suffixed.suffixes.table');
        $suffixTableCode = config('suffixed.suffixes.column');

        return ($suffixTable::where($suffixTableCode, $suffixCode)->count() !== 0);
    }

    /**
     * @param string $migrationFile
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createTables(string $migrationFile)
    {
        $class = $this->migrationFileToClassName($migrationFile);

        (new Filesystem)->requireOnce($migrationFile);

        $this->migrate($class);
    }

    /**
     * @param $migrationClass
     */
    protected function migrate(string $migrationClass)
    {
        $class = new $migrationClass();
        $class->suffixedTable ??= false;

        if ($class->suffixedTable) {
            $class->createTable($this->suffix);
        }
    }

    /**
     * @param $path
     * @return string
     */
    protected function migrationFileToClassName(string $path)
    {
        return $this->getMigrationClass(
            $this->getMigrationName($path)
        );
    }

    // Laravel Migrator : vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:538
    protected function getMigrationName(string $path)
    {
        return str_replace('.php', '', basename($path));
    }

    // Laravel Migrator : vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:497
    protected function getMigrationClass(string $migrationName): string
    {
        return Str::studly(implode('_', array_slice(explode('_', $migrationName), 4)));
    }
}
