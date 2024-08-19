<?php

namespace Fatihirday\Suffixed\Console;

use \Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use \Fatihirday\Suffixed\Databases\MigrationCreator;
use \Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class MakeMigrateSuffixCommand extends MigrateMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration-suffix {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migration command for suffixed tables';

    /**
     * @return string
     */
    protected function getStub()
    {
        return dirname(__DIR__) . '/stubs';
    }
    
    public function __construct()
    {
        $creator = new  MigrationCreator(
            new Filesystem(),
            $this->getStub()
        );

        parent::__construct($creator, app('composer'));
    }
}
