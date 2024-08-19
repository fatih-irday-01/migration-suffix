<?php

namespace Fatihirday\Suffixed\Databases;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    /**
     * @param $table
     * @param $create
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($table, $create)
    {
        if (is_null($table)) {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.stub')
                ? $customPath
                : $this->stubPath().'/migration.stub';
        } elseif ($create) {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.create.stub')
                ? $customPath
                : $this->stubPath().'/migration.create.stub';
        } else {
            $stub = $this->files->exists($customPath = $this->customStubPath.'/migration.update.stub')
                ? $customPath
                : $this->stubPath().'/migration.update.stub';
        }

        return $this->changeClassName($this->files->get($stub));
    }

    /**
     * @param $stub
     * @return string
     */
    public function changeClassName($stub): string
    {
        return str_replace(
            ['{{ class }}', '{{class}}'],
            $this->className(),
            $stub
        );
    }

    /**
     * @return string
     */
    public function className(): string
    {
        global $argv;
        $name = array_filter($argv, fn ($value) => !in_array($value, ['artisan', 'make:migration-suffix']));
        return Str::of(Arr::first($name))->camel()->ucfirst()->__toString() ?: 'Fatihirday';
    }
}
