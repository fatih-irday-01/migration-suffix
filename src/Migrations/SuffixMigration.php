<?php

namespace Fatihirday\Suffixed\Migrations;

use \Illuminate\Database\Migrations\Migration;

class SuffixMigration extends Migration
{
    protected $baseTableName = null;

    public $suffixedTable = true;

    protected function allTable(callable $method)
    {
        $suffixTableCode = config('suffixed.suffixes.column');

        config('suffixed.suffixes.table')::select($suffixTableCode)
            ->get()
            ->pluck($suffixTableCode)
            ->each($method);
    }

    public function createTable(string $suffix)
    {
        $this->upSuffix($this->getTableName($suffix), $suffix);
    }

    public function up()
    {
        $this->allTable(function ($item) {
            $this->upSuffix(
                $this->getTableName($item),
                $item
            );
        });
    }

    public function down()
    {
        $this->allTable(function ($item) {
            $this->downSuffix(
                $this->getTableName($item),
                $item
            );
        });
    }

    protected function getTableName(string $suffix): string
    {
        return strtr('{baseTable}{mergeOperator}{suffixCode}', [
            '{baseTable}' => $this->baseTableName,
            '{mergeOperator}' => config('suffixed.merge_operator', '_'),
            '{suffixCode}' => $suffix,
        ]);
    }
}
