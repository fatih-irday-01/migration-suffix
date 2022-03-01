<?php

namespace Fatihirday\Suffixed\Migrations;

use \Illuminate\Database\Migrations\Migration;

class SuffixMigration extends Migration
{
    protected $baseTableName = null;

    protected function allTable(string $method)
    {
        $suffixTableCode = config('suffixed.suffixes.column');

        config('suffixed.suffixes.table')::select($suffixTableCode)
            ->get()
            ->pluck($suffixTableCode)
            ->each(function ($item) use ($method) {
                $this->$method(
                    $this->getTableName($item)
                );
            });
    }

    public function up()
    {
        $this->allTable('upSuffix');
    }

    public function down()
    {
        $this->allTable('downSuffix');
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
