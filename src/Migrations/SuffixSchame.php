<?php

namespace Fatihirday\Suffixed\Migrations;

interface SuffixSchame
{
    public function upSuffix(string $tableName, string $prefix);
    public function downSuffix(string $tableName, string $prefix);
}
