<?php

namespace Fatihirday\Suffixed\Models;

use \Illuminate\Support\Str;
use \Illuminate\Database\Eloquent\Builder;

trait Suffixed
{
    /**
     * @var
     */
    private $suffixCode;

    /**
     * @param Builder $query
     * @param string $suffixCode
     * @return $this
     * @throws \Exception
     */
    public function scopeSetSuffixCode(Builder $query, string $suffixCode): self
    {
        if ($this->getSuffixedAutoCheck() && !$this::checkSuffixCode($suffixCode)) {
            throw new \Exception('Not Suffixed : ' . $suffixCode);
        }

        $this->setTable(strtr('{baseTable}{mergeOperator}{suffixCode}', [
            '{baseTable}' => $this->getTable(),
            '{mergeOperator}' => config('suffixed.merge_operator', '_'),
            '{suffixCode}' => $suffixCode,
        ]));

        $this->suffixCode = $suffixCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getSuffixCode(): string
    {
        return $this->suffixCode;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    /**
     * @param Builder $query
     * @param string $suffixCode
     * @return bool
     */
    public function scopeCheckSuffixCode(Builder $query, string $suffixCode): bool
    {
        $suffixTable = config('suffixed.suffixes.table');
        $suffixTableCode = config('suffixed.suffixes.column');

        return ($suffixTable::where($suffixTableCode, $suffixCode)->count() !== 0);
    }

    /**
     * @return bool
     */
    private function getSuffixedAutoCheck(): bool
    {
        return isset($this->suffix_auto_check) ?
            $this->suffix_auto_check :
            config('suffix_auto_check', true);
    }
}
