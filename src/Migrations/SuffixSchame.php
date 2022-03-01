<?php

namespace Fatihirday\Suffixed\Migrations;

interface SuffixSchame
{
    public function upSuffix(string $tableName);
    public function downSuffix(string $tableName);
}
